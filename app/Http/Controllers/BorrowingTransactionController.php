<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowingPolicy;
use App\Models\BorrowingTransaction;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PhpParser\Node\Expr\Cast\Object_;

class BorrowingTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', null);
        $sortDirection = $request->input('sort_direction', 'desc'); // Default to desc for latest first
        $filters = [];

        // Capture search parameters
        $searchTerm = $request->input('search');
        if (!empty($searchTerm)) {
            $filters[] = [
                'id' => 'search',
                'value' => $searchTerm
            ];
        }

        // Capture transaction type filter
        $transactionTypes = $request->input('transaction_type');
        if (!empty($transactionTypes)) {
            $filters[] = [
                'id' => 'transaction_type',
                'value' => is_array($transactionTypes) ? $transactionTypes : [$transactionTypes]
            ];
        }

        $latest_transactions = BorrowingTransaction::with('user')
            ->with('record')
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    // Search by transaction number (assuming you have a transaction_number field)
                    $q->where('transaction_number', 'like', '%' . $searchTerm . '%')
                        // Search by user name
                        ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                            $userQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                        })
                        // Search by record title
                        ->orWhereHas('record', function ($recordQuery) use ($searchTerm) {
                            $recordQuery->where('title', 'like', '%' . $searchTerm . '%')
                                ->orWhere('accession_number', 'like', '%' . $searchTerm . '%');
                        });
                });
            })
            ->when($transactionTypes, function ($query, $transactionTypes) {
                // Handle both single values and arrays
                $types = is_array($transactionTypes) ? $transactionTypes : [$transactionTypes];
                $query->whereIn('transaction_type', $types);
            })
            ->when($sortField, function ($query, $sortField) use ($sortDirection) {
                $query->orderBy($sortField, $sortDirection);
            }, function ($query) {
                // Default sorting when no sort field is specified
                $query->latest();
            })
            ->paginate(perPage: $perPage);

        return Inertia::render('borrowings/Index', [
            'data' => $latest_transactions,
            'filter' => $filters,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): \Inertia\Response
    {
        $search_result = null;
        $accession_number = $request->searchAcc ?? $request->scannedAcc;
        $patron = null;
        $searchPatron = $request->searchPatron;

        if ($accession_number) {
            $search_result = Record::where('accession_number', $accession_number)->first();
            if (!$search_result) {
                session()->flash('error', 'Sorry there is no record found');
            }
        }

        if ($searchPatron) {
            $patron = User::where('library_id', 'LIKE', "%{$searchPatron}%")
                ->orWhere('first_name', 'LIKE', "%{$searchPatron}%")
                ->orWhere('last_name', 'LIKE', "%{$searchPatron}%")
                ->select('id', 'first_name', 'last_name', 'library_id', 'email')
                ->first();
        }

        return Inertia::render('borrowings/Create', [
            'search_ac_result' => $search_result,
            'patron' => $patron,
        ]);
    }

    public function searchUser(Request $request)
    {
        $query = $request->get('q');

        \Log::info('Searching for user with query: ' . $query);

        $users = User::where('library_id', 'LIKE', "%{$query}%")
            ->orWhere('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->with(['student', 'faculty']) // Load related data
            ->select('id', 'first_name', 'last_name', 'email', 'library_id', 'user_type_id')
            ->get();

        \Log::info('Search results:', ['count' => $users->count(), 'users' => $users->toArray()]);

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'accession_number' => 'required|exists:records,accession_number',
                'borrow_type' => 'required|in:inside,take-home',
                'user_id' => 'required|exists:users,id',
            ]);

            $transaction = null;
            $record = Record::where('accession_number', $request->accession_number)->first();
            if (!$record) {
                session()->flash('error', 'Book not found');
                return to_route('borrowings.index');
            }
            if ($record->status === 'borrowed') {
                session()->flash('error', 'Book already borrowed');
                return to_route('borrowings.index');
            }

            if ($request->borrow_type === 'inside') {
                $insideBorrowingPolicy = BorrowingPolicy::where('name', 'Borrow Inside Policy')->first();
                if (!$insideBorrowingPolicy) {
                    Log::warning('Inside borrowing policy not found', [
                        'record_id' => $record->id,
                        'user_id' => Auth::id()
                    ]);
                    session()->flash('error', 'Inside borrowing policy not found');
                    return to_route('borrowings.index');
                }
                $transactionNumber = 'BRW-IN-' . date('YmdHis') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $transaction = BorrowingTransaction::create([
                    'transaction_number' => $transactionNumber,
                    'record_id' => $record->id,
                    'borrowing_policy_id' => $insideBorrowingPolicy->id,
                    'transaction_type' => 'borrow-inside',
                    'status' => 'borrowed-inside',
                    'checkout_date' => now(),
                    'checked_out_by' => Auth::id(),
                    'user_id' => $request->user_id,
                ]);
            } else if ($request->borrow_type === 'take-home') {
                $user = User::find($request->user_id);
                $policy = BorrowingPolicy::where('user_type_id', $user->user_type_id)->first();
                if (!$policy) {
                    session()->flash('error', 'Borrowing policy not found for user type');
                    return to_route('borrowings.index');
                }
                $transactionNumber = 'BRW-OUT-' . date('YmdHis') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $transaction = BorrowingTransaction::create([
                    'transaction_number' => $transactionNumber,
                    'user_id' => $user->id,
                    'record_id' => $record->id,
                    'borrowing_policy_id' => $policy->id,
                    'transaction_type' => 'checkout',
                    'status' => 'active',
                    'checkout_date' => now(),
                    'due_date' => now()->addDays($policy->loan_period_days),
                    'checked_out_by' => Auth::id(),
                ]);
            }
            $record->update(['status' => 'borrowed']);
            return to_route('borrowings.index')
                ->with('success', 'Borrowing transaction ' . $transaction->transaction_number . ' added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in BorrowingController@store', [
                'errors' => $e->errors(),
                'request' => $request->all(),
            ]);
            session()->flash('error', 'An error occurred while creating the borrowing transaction');

        } catch (\Exception $e) {
            Log::error('Error in BorrowingController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user_id' => Auth::id()
            ]);
            session()->flash('error', 'An error occurred while creating the borrowing transaction');
        }
    }

    public function borrow(Request $request)
    {
        $user = null;
        $policy = null;
        $book = null;
        try {
            $user = User::findOrFail($request->user_id);
            $book = Record::where('accession_number', $request->book_accession)->first();
            $policy = BorrowingPolicy::where('user_type_id', $user->user_type_id)->first();

            if ($book->status === 'borrowed') {
                session()->flash('error', 'Book already borrowed');
                return Inertia::render('borrowings/Create');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong');
            // Log the error
            \Log::error('Error in user or book retrieval: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'book_accession' => $request->book_accession,
                'exception' => $e
            ]);
        }

        $transactionNumber = 'BRW-OUT-' . date('YmdHis') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        $policy_loan_period_days = $policy->loan_period_days;

        $transaction = BorrowingTransaction::create([
            'transaction_number' => $transactionNumber,
            'user_id' => $user->id,
            'record_id' => $book->id,
            'borrowing_policy_id' => $policy->id,
            'transaction_type' => 'checkout',
            'status' => 'active',
            'checkout_date' => now(),
            'due_date' => now()->addDays($policy_loan_period_days),
            'checked_out_by' => Auth::id(),
        ]);

        $book->update([
            'status' => 'borrowed'
        ]);

        return to_route('borrowings.index')
            ->with('success', 'Borrowing transaction ' . $transaction->transaction_number . ' added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowingTransaction $borrowing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowingTransaction $borrowing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowingTransaction $borrowing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowingTransaction $borrowing)
    {
        //
    }
}
