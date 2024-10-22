<?php

namespace App\Repositories\Accounting;

use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;

class StudentFinancesRepository
{
    protected $invoiceRepo;
    protected $studentRegistrationRepo;
    protected $statementRepo;
    protected $quotationRepo;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        StudentRegistrationRepository $studentRegistrationRepository,
        StatementRepository $statementRepository,
        QuotationRepository $quotationRepository
    ) {
        $this->invoiceRepo = $invoiceRepository;
        $this->studentRegistrationRepo = $studentRegistrationRepository;
        $this->statementRepo = $statementRepository;
        $this->quotationRepo = $quotationRepository;
    }

    /**
     * Get a student's financial data (stats & balances)
     * 
     * @param  Student  $student The Student model instance
     * @return array
     * @author Blessed Zulu <bzulu@zut.edu.zm>
     */
    public function getStudentFinancialInfo($student)
    {
        $data = [];

        // Fetch balance percentage across all invoices
        $data['balancePercentage'] = $this->invoiceRepo->paymentPercentageAllInvoices($student->id);

        // Fetch active academic period info
        $data['academicPeriodInfo'] = $this->studentRegistrationRepo->getNextAcademicPeriod($student, now());

        // Fetch total fees, payments, and payment percentage
        $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodFeesTotal($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['totalPayments'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentsTotal($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['paymentPercentage'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentPercentage($student->id, $data['academicPeriodInfo']?->academic_period_id);

        // Calculate balances
        $data['paymentBalance'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentBalance($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['registrationBalance'] = 0;
        $data['viewResultsBalance'] = 0;

        // Check if student has been invoiced for the current academic period
        $data['studentInvoicedForCurrentAcademicPeriod'] = $this->invoiceRepo->checkStudentAcademicPeriodInvoiceStatus(
            $student,
            $data['academicPeriodInfo']?->academic_period_id
        );

        // Check if student has been quoted for the current academic period
        $data['studentQuotedForCurrentAcademicPeriod'] = $this->quotationRepo->checkStudentAcademicPeriodQuotationStatus(
            $student,
            $data['academicPeriodInfo']?->academic_period_id
        );

        // Adjust financial data if the student is invoiced for the current period
        if ($data['studentInvoicedForCurrentAcademicPeriod']) {
            $this->adjustFinancialDataForCurrentAcademicPeriod($data, $student);
        } else {
            $this->adjustFinancialDataForPreviousAcademicPeriod($data, $student);

            // Adjust registration balance if student has been quoted for the current academic period
            if ($data['studentQuotedForCurrentAcademicPeriod']) {
                $this->adjustFinancialDataForQuotedCurrentPeriod($data, $student);
            }
        }


        return $data;
    }

    private function calculatePercentage($cumulativeAmount, $total)
    {
        return $total == 0 ? 0 : (($cumulativeAmount / $total) * 100);
    }

    protected function adjustFinancialDataForPreviousAcademicPeriod(&$data, $student)
    {
        // Adjust data if a student is not invoiced in the current period
        $data['academicPeriodInfo'] = $this->invoiceRepo->latestPreviousAcademicPeriod($student);

        $data['totalFees'] = 0;
        $data['totalPayments'] = 0;
        $data['paymentPercentage'] = 0;
        $data['paymentBalance'] = 0;

        // If they have an unpaid balance from previous period
        if ($data['balancePercentage'] < 100) {
            $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodInvoicesTotal($student, $data['academicPeriodInfo']?->academic_period_id);

            $data['totalPayments'] = $this->statementRepo->getStudentAcademicPeriodStatementsTotal($student, $data['academicPeriodInfo']?->academic_period_id);
            $data['paymentPercentage'] = $this->calculatePercentage($data['totalPayments'], $data['totalFees']);

            $data['paymentBalance'] = $data['totalFees'] - $data['totalPayments'];

            // Calculate thresholds for registration and viewing results
            $data['registrationBalance'] = ($data['academicPeriodInfo']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
            $data['viewResultsBalance'] = ($data['academicPeriodInfo']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        }
    }

    protected function adjustFinancialDataForCurrentAcademicPeriod(&$data, $student)
    {
        // Adjust data if a student is invoiced in the current period
        $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodInvoicesTotal($student, $data['academicPeriodInfo']?->academic_period_id);
        $data['totalPayments'] = $this->statementRepo->getStudentAcademicPeriodStatementsTotal($student, $data['academicPeriodInfo']?->academic_period_id);

        $data['paymentBalance'] = $data['totalFees'] - $data['totalPayments'];
        $data['registrationBalance'] = ($data['academicPeriodInfo']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        $data['viewResultsBalance'] = ($data['academicPeriodInfo']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        $data['paymentPercentage'] = $this->calculatePercentage($data['totalPayments'], $data['totalFees']);
    }

    protected function adjustFinancialDataForQuotedCurrentPeriod(&$data, $student)
    {
        $data['academicPeriodInfo'] = $this->studentRegistrationRepo->getNextAcademicPeriod($student, now());

        $data['totalFees'] = $this->quotationRepo->getStudentAcademicPeriodQuotationsTotal(
            $student,
            $data['academicPeriodInfo']?->academic_period_id
        );

        $data['totalPayments'] = $this->statementRepo->getStudentNonInvoicedStatementsTotal(
            $student,
        );

        $data['registrationBalance'] = ($data['academicPeriodInfo']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
    }
}
