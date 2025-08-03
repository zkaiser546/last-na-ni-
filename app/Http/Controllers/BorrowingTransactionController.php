<?php

namespace App\Http\Controllers;

use App\Models\BorrowingPolicy;
use App\Models\BorrowingTransaction;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BorrowingTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $transactions = BorrowingTransaction::latest()->paginate(10);

        return Inertia::render('borrowings/Index', [
            'transactions' => $transactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): \Inertia\Response
    {
        $search_result = null;
        $accession_number = $request->searchAcc ?? $request->scannedAcc;

        if ($accession_number) {
            $search_result = Record::where('accession_number', $accession_number)->first();

            if (!$search_result) {
                session()->flash('error', 'Sorry there is no record found');
            }
        }

        return Inertia::render('borrowings/Create', [
            'search_ac_result' => $search_result,
        ]);
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
            ]);

            $transaction = null;

            if ($request->borrow_type === 'inside') {
                // Get the inside borrowing policy ID
                $insideBorrowingPolicy = BorrowingPolicy::where('name', 'Borrow Inside Policy')->first();

                if (!$insideBorrowingPolicy) {
                    Log::warning('Inside borrowing policy not found', [
                        'record_id' => $request->record_id,
                        'user_id' => Auth::id()
                    ]);
                    session()->flash('error', 'Inside borrowing policy not found');
                    return to_route('borrowings.index');
                }

                // Generate unique transaction number
                $transactionNumber = 'BRW-IN-' . date('YmdHis') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
                $record_id = Record::where('accession_number', $request->accession_number)->first()->id;

                if (!$record_id) {
                    session()->flash('error', 'Book not found');
                    return to_route('borrowings.index');
                }

                // Create the borrowing transaction
                $transaction = BorrowingTransaction::create([
                    'transaction_number' => $transactionNumber,
                    'record_id' => $record_id,
                    'borrowing_policy_id' => $insideBorrowingPolicy->id,
                    'transaction_type' => 'borrow-inside',
                    'status' => 'borrowed-inside',
                    'checkout_date' => now(),
                    'checked_out_by' => Auth::id(),
                ]);
            }

            else if ($request->borrow_type === 'take-home') {

                $user_type_id = User::where('id', $request->user_id)->first();

                dd($user_type_id);
                $borrowingPolicy = BorrowingPolicy::where('user_type_id', $user_type_id)->first();

            }

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
