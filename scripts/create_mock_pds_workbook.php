<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$output = __DIR__ . '/../storage/app/public/mock-pds-complete.xlsx';

$spreadsheet = new Spreadsheet();
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('PDS Page 1');
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('PDS Page 2');
$sheet3 = $spreadsheet->createSheet();
$sheet3->setTitle('PDS Page 3');
$sheet4 = $spreadsheet->createSheet();
$sheet4->setTitle('Import Meta');

$set = static function ($sheet, string $cell, mixed $value): void {
    $sheet->setCellValue($cell, $value);
};

// Sheet 1: personal, family, children, education
$set($sheet1, 'D10', 'DELA CRUZ');
$set($sheet1, 'D11', 'JUAN MIGUEL');
$set($sheet1, 'D12', 'SANTOS');
$set($sheet1, 'M12', 'JR');
$set($sheet1, 'D13', '1990-06-14');
$set($sheet1, 'D15', 'Davao City');
$set($sheet1, 'D16', 'Male');
$set($sheet1, 'D17', 'Married');
$set($sheet1, 'J13', 'Filipino');
$set($sheet1, 'J15', 'By birth');
$set($sheet1, 'L15', 'Philippines');
$set($sheet1, 'I17', 'Blk 8 Lot 3');
$set($sheet1, 'L17', 'Mabini Street');
$set($sheet1, 'I19', 'San Isidro Homes');
$set($sheet1, 'L19', 'Poblacion');
$set($sheet1, 'I22', 'Trento');
$set($sheet1, 'L22', 'Agusan del Sur');
$set($sheet1, 'I24', '8505');
$set($sheet1, 'I25', 'Purok 2');
$set($sheet1, 'L25', 'Quezon Avenue');
$set($sheet1, 'I27', 'Riverside Village');
$set($sheet1, 'L27', 'Manat');
$set($sheet1, 'I29', 'Trento');
$set($sheet1, 'L29', 'Agusan del Sur');
$set($sheet1, 'I31', '8505');
$set($sheet1, 'D22', '1.72');
$set($sheet1, 'D24', '68');
$set($sheet1, 'D25', 'O+');
$set($sheet1, 'D27', '1234-5678-9012');
$set($sheet1, 'D29', '1234-5678-9012');
$set($sheet1, 'D31', '12-345678901-2');
$set($sheet1, 'D32', '1234-5678-9012-3456');
$set($sheet1, 'D33', '123-456-789-000');
$set($sheet1, 'D34', 'EMP-2026-001');
$set($sheet1, 'I32', '(085) 123-4567');
$set($sheet1, 'I33', '09171234567');
$set($sheet1, 'I34', 'juan.delacruz@example.com');

$set($sheet1, 'D36', 'REYES');
$set($sheet1, 'D37', 'MARIA LUISA');
$set($sheet1, 'D38', 'RAMOS');
$set($sheet1, 'M37', '');
$set($sheet1, 'D39', 'Public School Teacher');
$set($sheet1, 'D40', 'DepEd Trento District');
$set($sheet1, 'D41', 'Poblacion, Trento, Agusan del Sur');
$set($sheet1, 'D42', '09181234567');
$set($sheet1, 'D43', 'DELA CRUZ');
$set($sheet1, 'D44', 'ROBERTO');
$set($sheet1, 'D45', 'SANTOS');
$set($sheet1, 'G44', 'SR');
$set($sheet1, 'D46', 'RAMOS');
$set($sheet1, 'D47', 'RAMOS');
$set($sheet1, 'D48', 'ELENA');
$set($sheet1, 'D49', 'MENDOZA');

$children = [
    ['name' => 'DELA CRUZ, JANELLA MAE', 'dob' => '2014-03-09'],
    ['name' => 'DELA CRUZ, JOSHUA PAUL', 'dob' => '2017-11-21'],
    ['name' => 'DELA CRUZ, JASMINE FAITH', 'dob' => '2021-08-05'],
];
for ($i = 0; $i < 12; $i++) {
    $row = 37 + $i;
    $child = $children[$i] ?? ['name' => '', 'dob' => ''];
    $set($sheet1, "I{$row}", $child['name']);
    $set($sheet1, "M{$row}", $child['dob']);
}

$education = [
    ['Baybay Elementary School', 'Elementary Curriculum', '1997', '2003', 'Graduated', '2003', 'With Honors'],
    ['Trento National High School', 'Secondary Education', '2003', '2007', 'Graduated', '2007', 'Third Honor'],
    ['Agusan Technical Institute', 'Computer Hardware Servicing NC II', '2008', '2009', 'Completed', '2009', 'TESDA Scholar'],
    ['University of Southeastern Philippines', 'Bachelor of Science in Information Technology', '2010', '2014', 'Graduated', '2014', 'Dean\'s Lister'],
    ['Ateneo de Davao University', 'Master in Public Administration', '2018', '2020', '18 units earned', '2020', 'Academic Excellence Award'],
];
for ($i = 0; $i < count($education); $i++) {
    $row = 54 + $i;
    [$school, $course, $from, $to, $units, $grad, $honor] = $education[$i];
    $set($sheet1, "D{$row}", $school);
    $set($sheet1, "G{$row}", $course);
    $set($sheet1, "J{$row}", $from);
    $set($sheet1, "K{$row}", $to);
    $set($sheet1, "L{$row}", $units);
    $set($sheet1, "M{$row}", $grad);
    $set($sheet1, "N{$row}", $honor);
}

// Sheet 2: eligibility and work experience
$eligibility = [
    ['Career Service Professional', '83.45', '2015-04-12', 'Butuan City', 'CS-PRO-2015-4481', 'N/A'],
    ['RA 1080', 'N/A', '2014-05-28', 'Commission on Higher Education', 'RA1080-IT-2020-0098', 'N/A'],
    ['Civil Service Subprofessional', '81.20', '2012-11-18', 'Davao City', 'CS-SUB-2012-1190', 'N/A'],
];
for ($i = 0; $i < 7; $i++) {
    $row = 5 + $i;
    $item = $eligibility[$i] ?? array_fill(0, 6, '');
    $set($sheet2, "A{$row}", $item[0]);
    $set($sheet2, "F{$row}", $item[1]);
    $set($sheet2, "G{$row}", $item[2]);
    $set($sheet2, "I{$row}", $item[3]);
    $set($sheet2, "J{$row}", $item[4]);
    $set($sheet2, "K{$row}", $item[5]);
}

$work = [
    ['2014-06-01', '2016-12-31', 'Administrative Aide IV', 'LGU Trento - HR Office', 'JO', 'Y'],
    ['2017-01-01', '2019-12-31', 'Administrative Assistant II', 'LGU Trento - HRMO', 'Permanent', 'Y'],
    ['2020-01-01', '2022-12-31', 'Human Resource Management Officer I', 'LGU Trento - HRMO', 'Permanent', 'Y'],
    ['2023-01-01', '2026-04-30', 'Human Resource Management Officer II', 'LGU Trento - HRMO', 'Permanent', 'Y'],
];
for ($i = 0; $i < 28; $i++) {
    $row = 18 + $i;
    $item = $work[$i] ?? array_fill(0, 6, '');
    $set($sheet2, "A{$row}", $item[0]);
    $set($sheet2, "C{$row}", $item[1]);
    $set($sheet2, "D{$row}", $item[2]);
    $set($sheet2, "G{$row}", $item[3]);
    $set($sheet2, "J{$row}", $item[4]);
    $set($sheet2, "K{$row}", $item[5]);
}

// Sheet 3: voluntary work, training, other info
$voluntary = [
    ['Trento Youth Development Council, Poblacion, Trento', '2016-01-15', '2017-12-15', '120', 'Volunteer Coordinator'],
    ['Parish Social Action Center, Trento', '2018-03-01', '2019-11-30', '96', 'Community Facilitator'],
    ['Agusan del Sur Disaster Response Volunteers', '2020-06-01', '2021-12-31', '180', 'Operations Volunteer'],
];
for ($i = 0; $i < 7; $i++) {
    $row = 6 + $i;
    $item = $voluntary[$i] ?? array_fill(0, 5, '');
    $set($sheet3, "A{$row}", $item[0]);
    $set($sheet3, "E{$row}", $item[1]);
    $set($sheet3, "F{$row}", $item[2]);
    $set($sheet3, "G{$row}", $item[3]);
    $set($sheet3, "H{$row}", $item[4]);
}

$trainings = [
    ['Strategic Human Resource Management for LGUs', '2021-02-10', '2021-02-12', '24', 'Managerial', 'CSC Regional Office XIII'],
    ['Records Management and Data Privacy Compliance', '2021-09-15', '2021-09-17', '24', 'Technical', 'DILG Caraga'],
    ['Public Service Continuity Planning Workshop', '2022-05-19', '2022-05-20', '16', 'Technical', 'Provincial Government of Agusan del Sur'],
    ['Digital Transformation in Local Government', '2023-07-05', '2023-07-07', '24', 'Executive / Managerial', 'DICT Mindanao Cluster'],
    ['Leadership Excellence for Public Managers', '2024-08-14', '2024-08-16', '24', 'Supervisory', 'Development Academy of the Philippines'],
];
for ($i = 0; $i < 21; $i++) {
    $row = 18 + $i;
    $item = $trainings[$i] ?? array_fill(0, 6, '');
    $set($sheet3, "B{$row}", $item[0]);
    $set($sheet3, "E{$row}", $item[1]);
    $set($sheet3, "F{$row}", $item[2]);
    $set($sheet3, "G{$row}", $item[3]);
    $set($sheet3, "H{$row}", $item[4]);
    $set($sheet3, "I{$row}", $item[5]);
}

$skills = [
    'Advanced spreadsheet reporting',
    'Public speaking',
    'Personnel records management',
    'Basic graphic design',
    'Community organizing',
    'Event facilitation',
    'Data encoding and QA',
];
$distinctions = [
    'Outstanding Employee of the Year 2023',
    'Service Excellence Award 2022',
    'Municipal Innovation Finalist 2021',
    'Best in Records Management 2020',
    'Employee Loyalty Award 2019',
    'Leadership Recognition 2018',
    'Community Service Citation 2017',
];
$memberships = [
    'Philippine Society for Human Resource Management',
    'National Association of Government HR Practitioners',
    'Local Government Records Officers Circle',
    'Philippine Red Cross Volunteer Corps',
    'Barangay Peacekeeping Action Team Alumni',
    'Municipal Sports Association',
    'Parish Family Ministry',
];
for ($i = 0; $i < 7; $i++) {
    $row = 42 + $i;
    $set($sheet3, "A{$row}", $skills[$i]);
    $set($sheet3, "C{$row}", $distinctions[$i]);
    $set($sheet3, "I{$row}", $memberships[$i]);
}

$metaRows = [
    ['personal.job_order', 'JO-TRN-2026-014'],
    ['other.questions.related_third_degree', 'No'],
    ['other.questions.related_fourth_degree_lgu', 'No'],
    ['other.questions.related_details', ''],
    ['other.questions.administrative_offense', 'No'],
    ['other.questions.administrative_offense_details', ''],
    ['other.questions.criminally_charged', 'No'],
    ['other.questions.criminal_date_filed', ''],
    ['other.questions.criminal_case_status', ''],
    ['other.questions.convicted_crime', 'No'],
    ['other.questions.convicted_crime_details', ''],
    ['other.questions.separated_service', 'No'],
    ['other.questions.separated_service_details', ''],
    ['other.questions.candidate_last_year', 'No'],
    ['other.questions.candidate_details', ''],
    ['other.questions.resigned_before_election', 'No'],
    ['other.questions.resigned_details', ''],
    ['other.questions.immigrant_status', 'No'],
    ['other.questions.immigrant_country', ''],
    ['other.questions.indigenous_group', 'No'],
    ['other.questions.indigenous_group_details', ''],
    ['other.questions.person_with_disability', 'No'],
    ['other.questions.pwd_id_no', ''],
    ['other.questions.solo_parent', 'No'],
    ['other.questions.solo_parent_id_no', ''],
    ['other.references.0.name', 'Atty. Carlos M. Benitez'],
    ['other.references.0.address', 'Municipal Hall, Trento, Agusan del Sur'],
    ['other.references.0.contact', 'cbenitez@trento.gov.ph / 09170000001'],
    ['other.references.1.name', 'Dr. Liza A. Romero'],
    ['other.references.1.address', 'Provincial Capitol, Prosperidad, Agusan del Sur'],
    ['other.references.1.contact', 'lromero@gov.ph / 09170000002'],
    ['other.references.2.name', 'Engr. Marvin T. Seno'],
    ['other.references.2.address', 'DILG Field Office, Butuan City'],
    ['other.references.2.contact', 'mseno@dilg.gov.ph / 09170000003'],
    ['other.government_id_type', 'Passport'],
    ['other.government_id_no', 'P1234567A'],
    ['other.government_id_date_place_issued', '2024-01-12 / DFA Davao'],
    ['other.date_accomplished', '2026-05-04'],
    ['other.signature_name', 'Juan Miguel Santos Dela Cruz'],
    ['other.visibility.show_contact', '1'],
    ['other.visibility.show_identifiers', '1'],
];

foreach ($metaRows as $index => [$path, $value]) {
    $row = $index + 1;
    $set($sheet4, "A{$row}", $path);
    $set($sheet4, "B{$row}", $value);
}

$sheet4->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

foreach ([$sheet1, $sheet2, $sheet3, $sheet4] as $sheet) {
    foreach (range('A', 'N') as $column) {
        $sheet->getColumnDimension($column)->setWidth(22);
    }
}

$writer = new Xlsx($spreadsheet);
$writer->save($output);

echo $output . PHP_EOL;
