<?php

namespace App\Repositories\Accounting;

use App\Repositories\Academics\StudentRegistrationRepository;
use App\Repositories\Accounting\InvoiceRepository;

class StudentFinancesRepository
{
    protected $invoiceRepo;
    protected $studentRegistrationRepo;
    protected $statementRepo;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        StudentRegistrationRepository $studentRegistrationRepository,
        StatementRepository $statementRepository
    ) {
        $this->invoiceRepo = $invoiceRepository;
        $this->studentRegistrationRepo = $studentRegistrationRepository;
        $this->statementRepo = $statementRepository;
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
        $data['academicPeriodInfo'] = $this->invoiceRepo->latestPreviousAcademicPeriod($student);

        $data['totalFees'] = 0;
        $data['totalPayments'] = 0;
        $data['paymentPercentage'] = 0;
        $data['paymentBalance'] = 0;

        // If they have an updaid balance from previous period
        if ($data['balancePercentage'] < 100) {
            $data['totalFees'] = $this->invoiceRepo->getStudentAcademicPeriodInvoicesTotal($student, $data['academicPeriodInfo']?->academic_period_id);

            $data['totalPayments'] = $this->statementRepo->getStudentAcademicPeriodStatementsTotal($student, $data['academicPeriodInfo']?->academic_period_id);
           
            if ($data['totalFees'] != 0) {
                $data['paymentPercentage'] = ($data['totalPayments'] / $data['totalFees']) * 100;
            } else {
                $data['paymentPercentage'] = 0;  // You can set this to null or another default value if desired
            }
            

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
        $data['paymentPercentage'] = $data['totalPayments'] / $data['totalFees'] * 100;
    }
}
