<?php

namespace App\Services;

use App\Imports\PdsWorkbookImport;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PdsFileParser
{
    public function __construct(private readonly PdsDataService $schema)
    {
    }

    public function parse(UploadedFile $file): array
    {
        return $this->parseExcel($file);
    }

    private function parseExcel(UploadedFile $file): array
    {
        $sheets = Excel::toArray(new PdsWorkbookImport(), $file);
        $c1 = $sheets[0] ?? [];
        $c2 = $sheets[1] ?? [];
        $c3 = $sheets[2] ?? [];
        $meta = $sheets[3] ?? [];

        $vmlChecked = $this->parseVmlChecked($file->getRealPath());

        $mapped = [
            'personal' => [
                'surname' => $this->cell($c1, 'D10'),
                'first_name' => $this->cell($c1, 'D11'),
                'middle_name' => $this->cell($c1, 'D12'),
                'name_extension' => $this->firstFilled($this->cell($c1, 'M12'), $this->cell($c1, 'L12')),
                'date_of_birth' => $this->normalizeDate($this->cell($c1, 'D13'), 'Y-m-d'),
                'place_of_birth' => $this->cell($c1, 'D15'),
                'sex_at_birth' => $this->normalizeSex($this->firstFilled($this->cell($c1, 'D16'), $vmlChecked['sex'] ?? null)),
                'civil_status' => $this->firstFilled($this->cell($c1, 'D17'), $vmlChecked['civil_status'] ?? null),
                'citizenship' => $this->firstFilled($this->cell($c1, 'J13'), $vmlChecked['citizenship'] ?? null),
                'citizenship_basis' => $this->firstFilled($this->cell($c1, 'J15'), $this->cell($c1, 'J16'), $vmlChecked['citizenship_basis'] ?? null),
                'dual_citizenship_country' => $this->firstFilled($this->cell($c1, 'L15'), $this->cell($c1, 'L17')),
                'height_m' => $this->cell($c1, 'D22'),
                'weight_kg' => $this->cell($c1, 'D24'),
                'blood_type' => $this->cell($c1, 'D25'),
                'umid_id_no' => $this->firstFilled($this->findIdByLabel($c1, ['UMID ID NO', 'UMID ID', 'UMID']), $this->cell($c1, 'D27')),
                'pagibig_id_no' => $this->firstFilled($this->findIdByLabel($c1, ['PAG-IBIG ID NO', 'PAG-IBIG ID', 'PAG-IBIG']), $this->cell($c1, 'D29')),
                'philhealth_no' => $this->firstFilled($this->findIdByLabel($c1, ['PHILHEALTH NO', 'PHILHEALTH']), $this->cell($c1, 'D30'), $this->cell($c1, 'D31')),
                'philsys_no' => $this->firstFilled($this->findIdByLabel($c1, ['PhilSys Number', 'PhilSys', 'PSN']), $this->cell($c1, 'D32')),
                'tin_no' => $this->firstFilled($this->findIdByLabel($c1, ['TIN NO', 'TIN']), $this->cell($c1, 'D32'), $this->cell($c1, 'D33')),
                'agency_employee_no' => $this->firstFilled($this->findIdByLabel($c1, ['AGENCY EMPLOYEE NO', 'AGENCY EMPLOYEE']), $this->cell($c1, 'D33'), $this->cell($c1, 'D34')),
                'telephone_no' => $this->cell($c1, 'I32'),
                'mobile_no' => $this->cell($c1, 'I33'),
                'email_address' => $this->cell($c1, 'I34'),
                'residential_house_no' => $this->cell($c1, 'I17'),
                'residential_street' => $this->cell($c1, 'L17'),
                'residential_subdivision' => $this->cell($c1, 'I19'),
                'residential_barangay' => $this->cell($c1, 'L19'),
                'residential_city' => $this->cell($c1, 'I22'),
                'residential_province' => $this->cell($c1, 'L22'),
                'residential_zip_code' => $this->cell($c1, 'I24'),
                'permanent_house_no' => $this->cell($c1, 'I25'),
                'permanent_street' => $this->cell($c1, 'L25'),
                'permanent_subdivision' => $this->cell($c1, 'I27'),
                'permanent_barangay' => $this->cell($c1, 'L27'),
                'permanent_city' => $this->cell($c1, 'I29'),
                'permanent_province' => $this->cell($c1, 'L29'),
                'permanent_zip_code' => $this->cell($c1, 'I31'),
            ],
            'family' => [
                'spouse_surname' => $this->cell($c1, 'D36'),
                'spouse_first_name' => $this->cell($c1, 'D37'),
                'spouse_middle_name' => $this->cell($c1, 'D38'),
                'spouse_name_extension' => $this->cell($c1, 'M37'),
                'spouse_occupation' => $this->cell($c1, 'D39'),
                'spouse_employer_business_name' => $this->cell($c1, 'D40'),
                'spouse_business_address' => $this->cell($c1, 'D41'),
                'spouse_telephone_no' => $this->cell($c1, 'D42'),
                'father_surname' => $this->cell($c1, 'D43'),
                'father_first_name' => $this->cell($c1, 'D44'),
                'father_middle_name' => $this->cell($c1, 'D45'),
                'father_name_extension' => $this->cell($c1, 'G44'),
                'mother_maiden_name' => $this->cell($c1, 'D46'),
                'mother_surname' => $this->cell($c1, 'D47'),
                'mother_first_name' => $this->cell($c1, 'D48'),
                'mother_middle_name' => $this->cell($c1, 'D49'),
            ],
            'children' => $this->mapChildren($c1),
            'education' => $this->mapEducation($c1),
            'eligibility' => $this->mapEligibility($c2),
            'work_experience' => $this->mapWorkExperience($c2),
            'voluntary_work' => $this->mapVoluntaryWork($c3),
            'trainings' => $this->mapTrainings($c3),
            'other' => [
                'special_skills_hobbies' => $this->columnValues($c3, 'A', 42, 48),
                'non_academic_distinctions' => $this->columnValues($c3, 'C', 42, 48),
                'memberships' => $this->columnValues($c3, 'I', 42, 48),
            ],
        ];

        return $this->schema->defaultData(array_replace_recursive(
            $mapped,
            $this->mapMetaOverrides($meta)
        ));
    }

    private function mapChildren(array $sheet): array
    {
        $children = [];
        for ($row = 37; $row <= 48; $row++) {
            $children[] = [
                'name' => $this->cell($sheet, "I{$row}"),
                'date_of_birth' => $this->normalizeDate($this->cell($sheet, "M{$row}")),
            ];
        }

        return $children;
    }

    private function mapEducation(array $sheet): array
    {
        $rows = [];
        foreach (PdsDataService::EDUCATION_LEVELS as $offset => $level) {
            $row = 54 + $offset;
            $rows[] = [
                'level' => $level,
                'school_name' => $this->cell($sheet, "D{$row}"),
                'degree_course' => $this->cell($sheet, "G{$row}"),
                'attendance_from' => $this->cell($sheet, "J{$row}"),
                'attendance_to' => $this->cell($sheet, "K{$row}"),
                'highest_level_units_earned' => $this->cell($sheet, "L{$row}"),
                'year_graduated' => $this->cell($sheet, "M{$row}"),
                'honors_received' => $this->cell($sheet, "N{$row}"),
                'sort_order' => $offset,
            ];
        }

        return $rows;
    }

    private function mapEligibility(array $sheet): array
    {
        $rows = [];
        for ($row = 5; $row <= 11; $row++) {
            $rows[] = [
                'career_service' => $this->cell($sheet, "A{$row}"),
                'rating' => $this->cell($sheet, "F{$row}"),
                'examination_date' => $this->normalizeDate($this->cell($sheet, "G{$row}")),
                'examination_place' => $this->cell($sheet, "I{$row}"),
                'license_number' => $this->cell($sheet, "J{$row}"),
                'license_valid_until' => $this->normalizeDate($this->cell($sheet, "K{$row}")),
            ];
        }

        return $rows;
    }

    private function mapWorkExperience(array $sheet): array
    {
        $rows = [];
        for ($row = 18; $row <= 45; $row++) {
            $rows[] = [
                'date_from' => $this->normalizeDate($this->cell($sheet, "A{$row}")),
                'date_to' => $this->normalizeDate($this->cell($sheet, "C{$row}")),
                'position_title' => $this->cell($sheet, "D{$row}"),
                'department_agency_office_company' => $this->cell($sheet, "G{$row}"),
                'status_of_appointment' => $this->cell($sheet, "J{$row}"),
                'government_service' => $this->cell($sheet, "K{$row}"),
            ];
        }

        return $rows;
    }

    private function mapVoluntaryWork(array $sheet): array
    {
        $rows = [];
        for ($row = 6; $row <= 12; $row++) {
            $rows[] = [
                'organization_name_address' => $this->cell($sheet, "A{$row}"),
                'date_from' => $this->normalizeDate($this->cell($sheet, "E{$row}")),
                'date_to' => $this->normalizeDate($this->cell($sheet, "F{$row}")),
                'number_of_hours' => $this->cell($sheet, "G{$row}"),
                'position_nature_of_work' => $this->cell($sheet, "H{$row}"),
            ];
        }

        return $rows;
    }

    private function mapTrainings(array $sheet): array
    {
        $rows = [];
        for ($row = 18; $row <= 38; $row++) {
            $rows[] = [
                'title' => $this->firstFilled($this->cell($sheet, "B{$row}"), $this->cell($sheet, "A{$row}")),
                'date_from' => $this->normalizeDate($this->cell($sheet, "E{$row}")),
                'date_to' => $this->normalizeDate($this->cell($sheet, "F{$row}")),
                'number_of_hours' => $this->cell($sheet, "G{$row}"),
                'type_of_ld' => $this->cell($sheet, "H{$row}"),
                'conducted_sponsored_by' => $this->cell($sheet, "I{$row}"),
            ];
        }

        return $rows;
    }

    private function columnValues(array $sheet, string $column, int $from, int $to): array
    {
        $values = [];
        for ($row = $from; $row <= $to; $row++) {
            $values[] = $this->cell($sheet, "{$column}{$row}");
        }

        return $values;
    }

    private function cell(array $sheet, string $address): ?string
    {
        preg_match('/^([A-Z]+)(\d+)$/', strtoupper($address), $matches);
        $column = $this->columnIndex($matches[1] ?? 'A');
        $row = ((int) ($matches[2] ?? 1)) - 1;
        $value = $sheet[$row][$column] ?? null;

        if (is_null($value)) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function columnIndex(string $column): int
    {
        $index = 0;
        foreach (str_split($column) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }

    private function normalizeDate(?string $value, string $format = 'm/d/Y'): ?string
    {
        if (! filled($value)) {
            return null;
        }

        if (is_numeric($value) && (float) $value > 10000) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format($format);
            } catch (\Throwable) {
                // fall through
            }
        }

        if (preg_match('/^\d{4}$/', $value)) {
            return $value;
        }

        if (preg_match('/[\/\-\.]/', $value)) {
            try {
                return Carbon::parse($value)->format($format);
            } catch (\Throwable) {
                return $value;
            }
        }

        return $value;
    }

    private function normalizeSex(?string $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        return Str::contains(Str::lower($value), 'female') ? 'Female' : (Str::contains(Str::lower($value), 'male') ? 'Male' : $value);
    }

    private function firstFilled(?string ...$values): ?string
    {
        foreach ($values as $value) {
            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }

    private function mapMetaOverrides(array $sheet): array
    {
        $overrides = [];

        foreach ($sheet as $row) {
            $path = trim((string) ($row[0] ?? ''));
            $value = $row[1] ?? null;

            if ($path === '') {
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            if ($value === '') {
                $value = null;
            }

            if (str_starts_with($path, 'other.visibility.')) {
                $value = in_array(Str::lower((string) $value), ['1', 'true', 'yes'], true);
            }

            data_set($overrides, $path, $value);
        }

        return $overrides;
    }

    private function parseVmlChecked(string $filePath): array
    {
        $checked = [
            'sex' => null,
            'civil_status' => null,
            'citizenship' => null,
            'citizenship_basis' => null,
        ];

        $zip = new \ZipArchive();
        if ($zip->open($filePath) === TRUE) {
            $vml = $zip->getFromName('xl/drawings/vmlDrawing1.vml');
            if ($vml) {
                $dom = new \DOMDocument();
                @$dom->loadXML($vml);
                $shapes = $dom->getElementsByTagNameNS('*', 'shape');
                
                foreach ($shapes as $shape) {
                    $clientData = $shape->getElementsByTagNameNS('*', 'ClientData');
                    if ($clientData->length > 0) {
                        $objectType = $clientData->item(0)->getAttribute('ObjectType');
                        if (strcasecmp($objectType, 'checkbox') === 0) {
                            $checkedNode = $clientData->item(0)->getElementsByTagNameNS('*', 'Checked');
                            $isChecked = $checkedNode->length > 0 && trim($checkedNode->item(0)->nodeValue) === '1';
                            
                            if ($isChecked) {
                                $textboxNode = $shape->getElementsByTagNameNS('*', 'textbox');
                                if ($textboxNode->length > 0) {
                                    $text = strip_tags($textboxNode->item(0)->nodeValue);
                                    $text = trim(preg_replace('/\s+/', ' ', $text));
                                    $textClean = strtolower($text);

                                    // Map Sex
                                    if (strpos($textClean, 'male') !== false && strpos($textClean, 'female') === false) {
                                        $checked['sex'] = 'Male';
                                    } elseif (strpos($textClean, 'female') !== false) {
                                        $checked['sex'] = 'Female';
                                    }

                                    // Map Civil Status
                                    if (strpos($textClean, 'single') !== false) {
                                        $checked['civil_status'] = 'Single';
                                    } elseif (strpos($textClean, 'married') !== false) {
                                        $checked['civil_status'] = 'Married';
                                    } elseif (strpos($textClean, 'widow') !== false) {
                                        $checked['civil_status'] = 'Widowed';
                                    } elseif (strpos($textClean, 'separated') !== false) {
                                        $checked['civil_status'] = 'Separated';
                                    } elseif (strpos($textClean, 'other') !== false) {
                                        $checked['civil_status'] = 'Others';
                                    }

                                    // Map Citizenship
                                    if (strpos($textClean, 'filipino') !== false) {
                                        $checked['citizenship'] = 'Filipino';
                                    } elseif (strpos($textClean, 'dual') !== false) {
                                        $checked['citizenship'] = 'Dual Citizenship';
                                    }

                                    // Map Citizenship Basis
                                    if (strpos($textClean, 'birth') !== false) {
                                        $checked['citizenship_basis'] = 'by birth';
                                    } elseif (strpos($textClean, 'naturalization') !== false) {
                                        $checked['citizenship_basis'] = 'by naturalization';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $zip->close();
        }

        return $checked;
    }

    private function findIdByLabel(array $sheet, array $labels): ?string
    {
        for ($rowIdx = 20; $rowIdx <= 40; $rowIdx++) {
            if (!isset($sheet[$rowIdx])) {
                continue;
            }
            $row = $sheet[$rowIdx];
            for ($colIdx = 0; $colIdx <= 2; $colIdx++) {
                $cellVal = isset($row[$colIdx]) ? trim((string)$row[$colIdx]) : '';
                if ($cellVal === '') {
                    continue;
                }
                foreach ($labels as $label) {
                    if (stripos($cellVal, $label) !== false) {
                        $value = isset($row[3]) ? trim((string)$row[3]) : null;
                        if ($value === '' || is_null($value)) {
                            return null;
                        }
                        return $value;
                    }
                }
            }
        }
        return null;
    }
}
