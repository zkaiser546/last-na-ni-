<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ClearanceController extends Controller
{
    public function index(Request $request)
    {
        // Dummy data for demonstration
        $students = [
            [ 'name' => 'Juan Dela Cruz', 'program' => 'BSIT' ],
            [ 'name' => 'Maria Santos', 'program' => 'BSCS' ],
            [ 'name' => 'Pedro Reyes', 'program' => 'BSA' ],
            [ 'name' => 'Ana Lopez', 'program' => 'BSED' ],
            [ 'name' => 'Jose Garcia', 'program' => 'BSCE' ],
            [ 'name' => 'Liza Ramos', 'program' => 'BSIT' ],
            [ 'name' => 'Mark Lim', 'program' => 'BSME' ],
            [ 'name' => 'Cathy Tan', 'program' => 'BSN' ],
        ];

        // Pagination logic (dummy)
        $page = (int)($request->input('page', 1));
        $perPage = (int)($request->input('per_page', 5));
        $total = count($students);
        $lastPage = (int)ceil($total / $perPage);
        $data = array_slice($students, ($page - 1) * $perPage, $perPage);

        return Inertia::render('Clearance', [
            'data' => [
                'data' => $data,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => $lastPage,
            ],
        ]);
    }

    public function export(Request $request)
    {
        $format = $request->input('format');
        $studentName = $request->input('student');

        $records = [
            [
                'date' => date('Y-m-d'),
                'reason' => 'Sample Reason',
                'academic_year' => '2024-2025',
                'semester' => '1st Semester',
                'remarks' => 'Sample Remarks'
            ],
        ];

        if ($format === 'pdf') {
            return $this->exportPdf($records, $studentName);
        } else if ($format === 'excel') {
            return $this->exportExcel($records, $studentName);
        }

        return response()->json(['error' => 'Invalid format'], 400);
    }

    private function exportPdf($records, $studentName)
    {
        $dompdf = new Dompdf();

        $html = '<html><head>';
        $html .= '<style>
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #f3f4f6; }
            h1 { text-align: center; }
        </style>';
        $html .= '</head><body>';
        $html .= '<h1>Clearance Records for ' . $studentName . '</h1>';
        $html .= '<table>';
        $html .= '<tr><th>Date</th><th>Reason</th><th>Academic Year</th><th>Semester</th><th>Remarks</th></tr>';

        foreach ($records as $record) {
            $html .= '<tr>';
            $html .= '<td>' . $record['date'] . '</td>';
            $html .= '<td>' . $record['reason'] . '</td>';
            $html .= '<td>' . $record['academic_year'] . '</td>';
            $html .= '<td>' . $record['semester'] . '</td>';
            $html .= '<td>' . $record['remarks'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'clearance-' . str_replace(' ', '-', strtolower($studentName)) . '.pdf';
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function exportExcel($records, $studentName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Clearance Records for ' . $studentName);
        $sheet->mergeCells('A1:E1');

        $headers = ['Date', 'Reason', 'Academic Year', 'Semester', 'Remarks'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue(chr(65 + $index) . '2', $header);
        }

        // Add data
        $row = 3;
        foreach ($records as $record) {
            $sheet->setCellValue('A' . $row, $record['date']);
            $sheet->setCellValue('B' . $row, $record['reason']);
            $sheet->setCellValue('C' . $row, $record['academic_year']);
            $sheet->setCellValue('D' . $row, $record['semester']);
            $sheet->setCellValue('E' . $row, $record['remarks']);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Style the header
        $sheet->getStyle('A1:E2')->getFont()->setBold(true);
        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal('center');

        $writer = new Xlsx($spreadsheet);
        $filename = 'clearance-' . str_replace(' ', '-', strtolower($studentName)) . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
