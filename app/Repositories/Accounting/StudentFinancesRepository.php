<?php

namespace App\Repositories\Accounting;

use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;

class StudentFinancesRepository
{
    protected $invoiceRepo;
    protected $studentRegistrationRepo;

    public function __construct(
        InvoiceRepository $invoiceRepo,
        StudentRegistrationRepository $studentRegistrationRepo,
    ) {
        $this->invoiceRepo = $invoiceRepo;
        $this->studentRegistrationRepo = $studentRegistrationRepo;
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

        // Check if student invoiced for the current academic period
        $studentInvoicedForCurrentAcademicPeriod = $this->invoiceRepo->checkStudentAcademicPeriodInvoiceStatus(
            $student,
            $data['academicPeriodInfo']?->academic_period_id
        );

        // Adjust financial data if the student isn't invoiced for the current period
        if (!$studentInvoicedForCurrentAcademicPeriod) {
            $this->adjustFinancialDataForPreviousAcademicPeriod($data, $student);
        } else {
            $this->adjustFinancialDataForCurrentAcademicPeriod($data, $student);
        }

        return $data;
    }

    protected function adjustFinancialDataForPreviousAcademicPeriod(&$data, $student)
    {
        // Adjust data if a student is not invoiced in the current period
        // if ($data['balancePercentage'] < 100) {
        $data['academicPeriodInfo'] = $this->invoiceRepo->latestPreviousAcademicPeriod($student);
        $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodFeesTotal($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['totalPayments'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentsTotal($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['paymentPercentage'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentPercentage($student->id, $data['academicPeriodInfo']?->academic_period_id);
        $data['paymentBalance'] = $this->invoiceRepo->getStudentAcademicPeriodPaymentBalance($student->id, $data['academicPeriodInfo']?->academic_period_id);

        // Calculate thresholds for registration and viewing results
        $data['registrationBalance'] = ($data['academicPeriodInfo']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        $data['viewResultsBalance'] = ($data['academicPeriodInfo']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        // }
    }

    protected function adjustFinancialDataForCurrentAcademicPeriod(&$data, $student)
    {
        // Adjust data if a student is invoiced in the current period
        $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodInvoicesTotal($student, $data['academicPeriodInfo']?->academic_period_id);
        $academicPeriodPaymentsTotal = $this->invoiceRepo->studentPaymentsAgainstInvoice($student, $data['academicPeriodInfo']?->academic_period_id);

        $data['paymentBalance'] = $data['totalFees'] - $academicPeriodPaymentsTotal;
        $data['totalPayments'] = $academicPeriodPaymentsTotal;
        $data['registrationBalance'] = ($data['academicPeriodInfo']?->registration_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        $data['viewResultsBalance'] = ($data['academicPeriodInfo']?->view_results_threshold / 100) * $data['totalFees'] - $data['totalPayments'];
        $data['paymentPercentage'] = $academicPeriodPaymentsTotal / $data['totalFees'] * 100;
    }
}
