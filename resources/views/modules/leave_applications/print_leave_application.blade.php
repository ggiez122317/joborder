@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader1">
        <tr>
            <td colspan="50" rowspan="1">Civil Service Form No. 6</td>
            <td colspan="50" rowspan="1">ANNEX A</td>
        </tr>
        <tr><td colspan="100" rowspan="1">Revised 2020</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">Republic of the Philippines</td></tr>
        <tr><td colspan="100" rowspan="1">MUNICIPALITY OF TRENTO</td></tr>
        <tr><td colspan="100" rowspan="1">Poblacion, Trento, Agusan del Sur</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">APPLICATION FOR LEAVE</td></tr>
    </table>

    <table id="tableBody1">
        <tr>
            <td colspan="30" rowspan="1">1. AGENCY/OFFICE</td>
            <td colspan="10" rowspan="1">2. NAME:</td>
            <td colspan="20" rowspan="1">(Last)</td>
            <td colspan="20" rowspan="1">(First)</td>
            <td colspan="20" rowspan="1">(Middle)</td>
        </tr>
        <tr>
            <td colspan="30" rowspan="1" class="office">-</td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="20" rowspan="1" class="lname">-</td>
            <td colspan="20" rowspan="1" class="fname">-</td>
            <td colspan="20" rowspan="1" class="mname">-</td>
        </tr>
        <tr>
            <td colspan="14" rowspan="1">3. DATE OF FILING:</td>
            <td colspan="16" rowspan="1" class="dateFiled">-</td>
            <td colspan="10" rowspan="1">4. POSITION:</td>
            <td colspan="25" rowspan="1" class="position">-</td>
            <td colspan="10" rowspan="1">5. SALARY:</td>
            <td colspan="25" rowspan="1" class="salary">-</td>
        </tr>
    </table>
    <table id="tableBody21"><tr><td colspan="100" rowspan="1">6. DETAILS OF APPLICATION</td></tr></table>
    <table id="tableBody22">
        <tr><td colspan="100" rowspan="1">6.A TYPE OF LEAVE TO BE AVAILED OF </td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Vacation Leave (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Mandatory/Forced Leave (Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Sick Leave (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Maternity Leave (R>A. No. 11210/IRR issued by CSC, DOLE and SSS)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Paternity Leave (R.A. No. 8187/CSC MC No. 71,s 1998, as amended)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Special Privilege Leave (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Solo Parent Leave (R.A. No. 8972 / CSC MC No. 8,s 2004)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Study Leave (Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">10-Day VAWC Leave (R.A. No. 9262/CSC MC No. 15, s. 2005)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Rehabilitation Privilege (Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Special Leave Benefits for Women (R. A. No. 9710/CSC MC No. 25, s. 2010)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Special Emergency (Calamity) Leave (CSC MC No. 2, s. 2012, as amended)</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Adoption Leave (R.A. No. 8552)</td>
        </tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">Others:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="leaveTypeDetail">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1"></td>
            <td colspan="4" rowspan="1"></td>
        </tr>

        <!-- ****************** -->
        <tr><td colspan="100" rowspan="1">6.C NUMBER OF DAYS APPLIED FOR</td></tr>

        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="80" rowspan="1" class="daysApplied">-</td>
            <td colspan="10" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="90" rowspan="1">INCLUSIVE DATES</td>
        </tr>

        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="80" rowspan="1" class="datesInclusive">-</td>
            <td colspan="10" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="80" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
        </tr>

    </table>
    <table id="tableBody23">
        <tr><td colspan="100" rowspan="1">6.B DETAILS OF LEAVE</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">In case of Vacation/ Special Privilege Leave:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="40" rowspan="1">Within the Philippines</td>
            <td colspan="46" rowspan="1"></td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="30" rowspan="1">Abroad(Specify)</td>
            <td colspan="56" rowspan="1" class="leaveCaseDetailVacation">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">In case of Sick Leave:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="50" rowspan="1">In Hospital (Specify Illness)</td>
            <td colspan="36" rowspan="1"></td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="50" rowspan="1">Out Patient (Specify Illness)</td>
            <td colspan="36" rowspan="1"></td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="leaveCaseDetailSick">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>

        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">In case of Special Leave Benefits for Women:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="30" rowspan="1">(Specify Ilness)</td>
            <td colspan="62" rowspan="1"></td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="leaveCaseDetailWomen">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">In case of Study Leave:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Completion of Master's Degree</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">BAR/Board Examination Review</td>
        </tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="96" rowspan="1">Other Purposes:</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Monitization of Leave Credits</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Terminal Leave</td>
        </tr>

        <!-- ****************** -->
        <tr><td colspan="100" rowspan="1">6.D COMMUTATION</td></tr>

        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Not Requested</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">Requested</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="80" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="80" rowspan="1">(Singature of Applicant)</td>
            <td colspan="10" rowspan="1"></td>
        </tr>

    </table>

    <table id="tableBody24"><tr><td colspan="100" rowspan="1">7. DETAILS OF ACTION ON APPLICATION</td></tr></table>
    <table id="tableBody25">
        <tr><td colspan="100" rowspan="1">7.A CERTIFICATION OF LEAVE CREDITS</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <!--  -->
        <tr>
            <td colspan="50" rowspan="1">As of</td>
            <td colspan="35" rowspan="1" class="creditsStatusAsOfMonth">-</td>
            <td colspan="15" rowspan="1"></td>
        </tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
            <td colspan="29" rowspan="1">Vacation Leave</td>
            <td colspan="29" rowspan="1">Sick Leave</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="34" rowspan="1">Total Earned</td>
            <td colspan="29" rowspan="1" class="creditsVacationEarned">-</td>
            <td colspan="29" rowspan="1" class="creditsSickEarned">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="34" rowspan="1">Less this application</td>
            <td colspan="29" rowspan="1" class="creditsVacationLess">-</td>
            <td colspan="29" rowspan="1" class="creditsSickLess">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="34" rowspan="1">Balance</td>
            <td colspan="29" rowspan="1" class="creditsVacationBalance">-</td>
            <td colspan="29" rowspan="1" class="creditsSickBalance">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1" class="checkedBy">-</td></tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="checkerPos">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr><td colspan="100" rowspan="1">(Authorized Officer)</td></tr>

    </table>
    <table id="tableBody26">
        <tr><td colspan="100" rowspan="1">7.B RECOMMENDATION</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">For Approval</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="6" rowspan="1"></td>
            <td colspan="90" rowspan="1">For disapproval due to:</td>
        </tr>

        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="86" rowspan="1" class="disapproveRemarksRecommeder">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr><td colspan="100" rowspan="1">&nbsp;<br /></td></tr>
        
        <!--  -->
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="recommendedBy">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1" class="recommenderPos">-</td>
            <td colspan="4" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="4" rowspan="1"></td>
            <td colspan="92" rowspan="1">(Authorized Official)</td>
            <td colspan="4" rowspan="1"></td>
        </tr>

    </table>

    <table id="tableBodyLast1">
        <tr>
            <td colspan="56" rowspan="1">7.C APPROVED FOR</td>
            <td colspan="44" rowspan="1">7.D DISAPPROVED DUE TO</td>
        </tr>
        <tr>
            <td colspan="6" rowspan="1"></td>
            <td colspan="20" rowspan="1" class="approvalType1">-</td>
            <td colspan="30" rowspan="1">days with pay</td>
            <td colspan="44" rowspan="3" class="disapproveRemarksApprover">-</td>
        </tr>
        <tr>
            <td colspan="6" rowspan="1"></td>
            <td colspan="20" rowspan="1" class="approvalType2">-</td>
            <td colspan="30" rowspan="1">days without pay</td>
        </tr>
        <tr>
            <td colspan="6" rowspan="1"></td>
            <td colspan="20" rowspan="1" class="approvalType3">-</td>
            <td colspan="30" rowspan="1">others (Specify)</td>
        </tr>
    </table>
    <table id="tableBodyLast2">
        <tr>
            <td colspan="30" rowspan="1"></td>
            <td colspan="40" rowspan="1" class="approvedBy">-</td>
            <td colspan="30" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="30" rowspan="1"></td>
            <td colspan="40" rowspan="1" class="approverPos">-</td>
            <td colspan="30" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="30" rowspan="1"></td>
            <td colspan="40" rowspan="1">Authorized Official</td>
            <td colspan="30" rowspan="1"></td>
        </tr>
    </table>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        
        let docTitle = "Leave Application"
        let headerImage1 = "{{ $headerImage1 }}"
        let headerImage3 = "{{ $headerImage3 }}"
        let imageCheck = "{{ $imageCheck }}"
        let show = "{{ $show }}"

        let signatureApplicant      = ''
        let signatureChecker        = ''
        let signatureRecommender    = ''
        let signatureApprover       = ''

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = inches_1/2
        let imageSize = 18
        let imageOpaqueSize = imageSize * 8 
        const imageSignatureWidth   = 30
        const imageSignatureHeight  = 10
        let marginFromCenter = 55
        
        const imageCheckSize  = 2.5

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'A4',
                putOnlyUsedFonts:true,
            })
            doc.setProperties({
                title       : `${getCurrentDateTime()}_${docTitle.split(' ').join('_')}`,
                subject     : '...',
                author      : 'Rogincel Demata',
                keywords    : 'jsPDF, PDF, example',
                creator     : 'DeTech'
            })
            widthPage = doc.internal.pageSize.getWidth()
            widthWithoutMargin = widthPage - inches_1

            getItems(doc, widthPage)
            return
            
        } 

        async function generateQrcode(doc, url, y)
        {

            doc.addImage(await QRCode.toDataURL(url), 'PNG', (inches_1*.5)-1, y-1, 20, 20)

        }

        function generatePageData(data)
        {

            tableID = 'tableBody1'
            document.querySelectorAll(`#${tableID} .office`).forEach(el => el.textContent = data.row.office)
            document.querySelectorAll(`#${tableID} .lname`).forEach(el => el.textContent = data.row.lname)
            document.querySelectorAll(`#${tableID} .fname`).forEach(el => el.textContent = data.row.fname)
            document.querySelectorAll(`#${tableID} .mname`).forEach(el => el.textContent = data.row.mname)
            document.querySelectorAll(`#${tableID} .dateFiled`).forEach(el => el.textContent = data.row.dateFiled)
            document.querySelectorAll(`#${tableID} .position`).forEach(el => el.textContent = data.row.position)
            document.querySelectorAll(`#${tableID} .salary`).forEach(el => el.textContent = data.row.salary)
            tableID = 'tableBody22'
            document.querySelectorAll(`#${tableID} .leaveTypeDetail`).forEach(el => el.textContent = data.row.leaveTypeDetail)
            document.querySelectorAll(`#${tableID} .daysApplied`).forEach(el => el.textContent = data.row.daysApplied)
            document.querySelectorAll(`#${tableID} .datesInclusive`).forEach(el => el.textContent = data.row.datesInclusive)
            tableID = 'tableBody23'
            if ([1,6].includes(data.row.leaveTypeID) && data.row.leaveCaseID==2) document.querySelectorAll(`#${tableID} .leaveCaseDetailVacation`).forEach(el => el.textContent = data.row.leaveCaseDetail)
            if (data.row.leaveTypeID==3 && data.row.leaveCaseID==4) document.querySelectorAll(`#${tableID} .leaveCaseDetailSick`).forEach(el => el.textContent = data.row.leaveCaseDetail)
            if (data.row.leaveTypeID==11) document.querySelectorAll(`#${tableID} .leaveCaseDetailWomen`).forEach(el => el.textContent = data.row.leaveCaseDetail)
            tableID = 'tableBody25'
            document.querySelectorAll(`#${tableID} .creditsStatusAsOfMonth`).forEach(el => el.textContent = data.row.creditsStatusAsOfMonth)
            document.querySelectorAll(`#${tableID} .creditsVacationEarned`).forEach(el => el.textContent = data.row.creditsVacationEarned)
            document.querySelectorAll(`#${tableID} .creditsVacationLess`).forEach(el => el.textContent = data.row.creditsVacationLess)
            document.querySelectorAll(`#${tableID} .creditsVacationBalance`).forEach(el => el.textContent = data.row.creditsVacationBalance)
            document.querySelectorAll(`#${tableID} .creditsSickEarned`).forEach(el => el.textContent = data.row.creditsSickEarned)
            document.querySelectorAll(`#${tableID} .creditsSickLess`).forEach(el => el.textContent = data.row.creditsSickLess)
            document.querySelectorAll(`#${tableID} .creditsSickBalance`).forEach(el => el.textContent = data.row.creditsSickBalance)
            document.querySelectorAll(`#${tableID} .checkedBy`).forEach(el => el.textContent = data.row.checkedBy)
            document.querySelectorAll(`#${tableID} .checkerPos`).forEach(el => el.textContent = data.row.checkerPos)
            tableID = 'tableBody26'
            if (data.row.dateRecommended == '' && data.row.status < 0 && data.row.recommender==data.row.disapprover) {
                document.querySelectorAll(`#${tableID} .disapproveRemarksRecommeder`).forEach(el => el.textContent = data.row.disapproveRemarks)
            }
            document.querySelectorAll(`#${tableID} .recommendedBy`).forEach(el => el.textContent = data.row.recommendedBy)
            document.querySelectorAll(`#${tableID} .recommenderPos`).forEach(el => el.textContent = data.row.recommenderPos)
            tableID = 'tableBodyLast1'
            document.querySelectorAll(`#${tableID} .approvalType1`).forEach(el => el.textContent = '')
            document.querySelectorAll(`#${tableID} .approvalType2`).forEach(el => el.textContent = '')
            document.querySelectorAll(`#${tableID} .approvalType3`).forEach(el => el.textContent = '')
            document.querySelectorAll(`#${tableID} .disapproveRemarksApprover`).forEach(el => el.textContent = '')
            if (data.row.approvalType == 1) document.querySelectorAll(`#${tableID} .approvalType1`).forEach(el => el.textContent = data.row.approvalDetail)
            if (data.row.approvalType == 2) document.querySelectorAll(`#${tableID} .approvalType2`).forEach(el => el.textContent = data.row.approvalDetail)
            if (data.row.approvalType == 3) document.querySelectorAll(`#${tableID} .approvalType3`).forEach(el => el.textContent = data.row.approvalDetail)
            if (data.row.status < 0 && data.row.dateChecked && data.row.dateRecommended) {
                document.querySelectorAll(`#${tableID} .disapproveRemarksApprover`).forEach(el => el.textContent = data.row.disapproveRemarks)
            }
            tableID = 'tableBodyLast2'
            document.querySelectorAll(`#${tableID} .approvedBy`).forEach(el => el.textContent = data.row.approvedBy)
            document.querySelectorAll(`#${tableID} .approverPos`).forEach(el => el.textContent = data.row.approverPos)

        }

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-leave-application-data/{{ $id }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    (async () => {
                        if (res.status == 200) {

                            generatePageData(res.items)

                            // signatures
                            signatureApplicant      = res.items.row.signatureApplicant
                            signatureChecker        = res.items.row.signatureChecker
                            signatureRecommender    = res.items.row.signatureRecommender
                            signatureApprover       = res.items.row.signatureApprover

                            tables2CenterMargin = 2
                            tables2Width = widthWithoutMargin - tables2CenterMargin

                            tables21Width = tables2Width*0.56
                            tables22Width = tables2Width*0.44

                            tables1MarginLeft  = inches_1/2
                            tables1MarginRight = inches_1/2

                            tables20MarginLeft  = inches_1/2 + 0.5
                            tables20MarginRight = inches_1/2 + 0.5

                            tables21MarginLeft  = inches_1/2 + 0.5
                            tables21MarginRight = inches_1/2 + 0.5 + tables22Width + 1
                            tables22MarginLeft  = inches_1/2 + 0.5 + tables21Width + 1
                            tables22MarginRight = inches_1/2 + 0.5
        
                            numColumns = 100
                            
                            columnWidth = tables2Width / numColumns
                            columnStyles = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles[i] = { cellWidth: columnWidth };
                            }
                            
                            columnWidth1 = tables21Width / numColumns
                            columnStyles1 = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles1[i] = { cellWidth: columnWidth1 };
                            }

                            columnWidth2 = tables22Width / numColumns
                            columnStyles2 = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles2[i] = { cellWidth: columnWidth2 };
                            }

                            /** ****************** PAGE 1 ****************** */
                            

                            // ************* HEADER *************

                            doc.autoTable({
                                html: '#tableHeader1', 
                                theme: 'plain', 
                                startY: y,  
                                margin: { left: tables1MarginLeft, right: tables1MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.2, bottom: 0.2 }

                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'top'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if (data.row.index === 0) {
                                        if (data.column.index === 0) {
                                            data.cell.styles.halign = 'left'
                                        } else {
                                            data.cell.styles.halign = 'right'

                                        }
                                    }
                                    if (data.row.index === 1) data.cell.styles.halign = 'left'
                                    if ([4,7].includes(data.row.index)) data.cell.styles.fontStyle = 'bold'
                                    if ([7].includes(data.row.index)) data.cell.styles.fontSize = 12

                                },
                            })
                            dY = y+10
                            doc.addImage(headerImage1, 'PNG', (widthPage/2)-marginFromCenter, y + 10, imageSize, imageSize) 
                            doc.addImage(headerImage3, 'PNG', ((widthPage/2)-(imageOpaqueSize/2))+2, y+50, imageOpaqueSize, imageOpaqueSize)

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 1

                            // ************* BODY *************

                            doc.autoTable({
                                html: '#tableBody1', 
                                theme: 'plain', 
                                startY: y,  
                                margin: { left: tables1MarginLeft, right: tables1MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.5, bottom: 0.5 }

                                    data.cell.styles.fontSize       = 7
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([1,2].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top+1, bottom: defaultPadding.bottom+1 }
                                    }
                                    if ([1].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 9
                                        data.cell.styles.fontStyle = 'bold'
                                    }
                                    if ([2].includes(data.row.index)) {
                                        if ([14].includes(data.column.index)) {
                                            data.cell.styles.fontSize = 8
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([40,75].includes(data.column.index)) {
                                            data.cell.styles.fontSize = 9
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                    }

                                    // border 
                                    if (data.row.index === 1) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                    }

                                },
                            })

                            // 
                            y += (doc.autoTable.previous.finalY - y) 
                            yStart = y 
                            yEnd = 0

                            doc.autoTable({
                                html: '#tableBody21', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables20MarginLeft, right: tables20MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'bold'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                },
                            })
                            
                            yEnd += doc.autoTable.previous.finalY - y
                            y += (doc.autoTable.previous.finalY - y) 

                            doc.autoTable({
                                html: '#tableBody22', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables21MarginLeft, right: tables21MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles1, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if (data.row.index > 18) {
                                        data.cell.styles.halign = 'center'
                                    }
                                    if (data.row.index === 20) {
                                        data.cell.styles.halign = 'center'
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top+0.5, bottom: defaultPadding.bottom+0.5 }
                                    }
                                    if ([20].includes(data.row.index)) {
                                        data.cell.styles.halign = 'left'
                                    }
                                    if ([19,21].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 10
                                    }

                                    // border 
                                    if ([3,7,11].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 6
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0.5, bottom: 0.5 }
                                    }
                                    if ([2,4,5,6,8,9,10,12,13,14].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 7
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0.5, bottom: 0.5 }
                                    }
                                    if ([16].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top+2.25, bottom: defaultPadding.bottom }
                                    }
                                    if ([16].includes(data.row.index)) {
                                        if ([4].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([16].includes(data.row.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.halign = 'center'
                                        data.cell.styles.fontSize = 10
                                    }
                                    if ([19,21].includes(data.row.index)) {
                                        if ([10].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }

                                },
                                didDrawCell: function (data) {

                                    const textPos = data.cell;
                                    const textX = textPos.x + 1.5
                                    const textY = textPos.y + 0.5

                                    if ([2,3,4,5,6,7,8,9,10,11,12,13,14].includes(data.row.index) & data.column.index === 4) {
                                        let doc = data.doc;
                                        let x = data.cell.x;
                                        let y = data.cell.y;
                                        let width = data.cell.width;
                                        let height = data.cell.height;

                                        let col1Width = width * 0.4;
                                        let col2Width = width * 0.6;

                                        // Draw outer rectangle (main box)
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y+0.3, (width - 0.5), 3); 

                                        // FILL BG COLOR 
                                        if (res.items.row.leaveTypeID == 1 && data.row.index === 2 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 2 && data.row.index === 3 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 3 && data.row.index === 4 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 4 && data.row.index === 5 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 5 && data.row.index === 6 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 6 && data.row.index === 7 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 7 && data.row.index === 8 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 8 && data.row.index === 9 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 9 && data.row.index === 10 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 10 && data.row.index === 11 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 11 && data.row.index === 12 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 12 && data.row.index === 13 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 
                                        if (res.items.row.leaveTypeID == 13 && data.row.index === 14 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize) 

                                    }
                                }
                            })
                            tbl1Height = (doc.autoTable.previous.finalY - y)
                            doc.autoTable({
                                html: '#tableBody23', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables22MarginLeft, right: tables22MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles2, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([21,22].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                    }
                                    if ([21].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top+2.5, bottom: defaultPadding.bottom }
                                    }
                                    if ([22].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                        data.cell.styles.fontStyle = 'italic'
                                    }

                                    // border
                                    if ([8,11].includes(data.row.index)) {
                                        if ([4].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([17].includes(data.row.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom+1 }
                                    }
                                    if ([21].includes(data.row.index)) {
                                        if ([10].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([40].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                    }

                                },
                                didDrawCell: function (data) {

                                    const textPos = data.cell 
                                    const textX = textPos.x + 1 
                                    const textY = textPos.y + 0.5 

                                    if ([3,4,6,7,13,14,16,17,19,20].includes(data.row.index) & data.column.index === 4) {
                                        let doc = data.doc;
                                        let x = data.cell.x;
                                        let y = data.cell.y;
                                        let width = data.cell.width;
                                        let height = data.cell.height;

                                        let col1Width = width * 0.4;
                                        let col2Width = width * 0.6;


                                        // Draw outer rectangle (main box)
                                        doc.setDrawColor(0, 0, 0) 
                                        doc.setLineWidth(0.3)
                                        doc.rect(x, y+0.3, (width - 0.5), 3)

                                        if (res.items.row.leaveCaseID == 1 && data.row.index === 3 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveCaseID == 2 && data.row.index === 4 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveCaseID == 3 && data.row.index === 6 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveCaseID == 4 && data.row.index === 7 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveTypeID == 8 && res.items.row.leaveCaseID == 5 && data.row.index === 13 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveTypeID == 8 && res.items.row.leaveCaseID == 6 && data.row.index === 14 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveTypeID == 15 && data.row.index === 16 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.leaveTypeID == 16 && data.row.index === 17 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (!res.items.row.commutation && data.row.index === 19 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.commutation && data.row.index === 20 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)

                                    }

                                    if (show == 1) {
                                        const textPos = data.cell;
                                        const textX = textPos.x + imageSignatureWidth + 1 
                                        const textY = textPos.y - 1.5
    
                                        // signature
                                        if (data.row.index === 21 && data.column.index === 10 && signatureApplicant) {
                                            const textPos = data.cell;
                                            doc.addImage(
                                                signatureApplicant,
                                                'PNG',
                                                textPos.x,
                                                textPos.y - 3,
                                                imageSignatureWidth, // width
                                                imageSignatureHeight  // height
                                            )
                                            if (res.items.row.dateFiledSign)  {
                                                lines = [
                                                    'Digitally signed by',
                                                    res.items.row.appliedBySign,
                                                    res.items.row.dateFiledSign + ' +0800'
                                                ]

                                                const lineHeight = 2.8
                                                doc.setFontSize(8)
                                                doc.setFont('helvetica', 'normal')
                                                lines.forEach((line, i) => {
                                                    doc.text(line, textX, textY + i * lineHeight, { baseline: 'middle' })
                                                })
                                            }
                                        }
                                    }

                                }
                            })
                            tbl2Height = (doc.autoTable.previous.finalY - y)

                            tblHeight = tbl1Height > tbl2Height ? tbl1Height : tbl2Height

                            yEnd += tblHeight
                            y += tblHeight 

                            doc.autoTable({
                                html: '#tableBody24', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables20MarginLeft, right: tables20MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'bold'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                },
                            })

                            yEnd += doc.autoTable.previous.finalY - y
                            y += (doc.autoTable.previous.finalY - y) 

                            doc.autoTable({
                                html: '#tableBody25', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables21MarginLeft, right: tables21MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles2, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if (data.row.index > 7) {
                                        data.cell.styles.halign = 'center'
                                    }
                                    if (data.row.index === 2) {
                                        data.cell.styles.halign = 'right'
                                        if (data.column.index === 50) data.cell.styles.halign = 'left'
                                    }
                                    if (data.row.index === 8) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top + 6, bottom: defaultPadding.bottom }
                                    }
                                    if (data.row.index === 9) {
                                        data.cell.styles.fontSize = 9
                                        data.cell.styles.fontStyle = 'bold'
                                    }

                                    // border
                                    if ([10].includes(data.row.index)) {
                                        if ([4].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                        data.cell.styles.fontStyle = 'italic'
                                    }
                                    if ([2].includes(data.row.index)) {
                                        if ([50].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([4,5,6,7].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                        if ([4,38,67].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                        }
                                    }

                                },
                                didDrawCell: function (data) {

                                    const textPos = data.cell;
                                    const textX = textPos.x + imageSignatureWidth + 23  
                                    const textY = textPos.y - 6.5

                                    // if ([2,3].includes(data.row.index) & data.column.index === 4) {
                                    //     let doc = data.doc;
                                    //     let x = data.cell.x;
                                    //     let y = data.cell.y;
                                    //     let width = data.cell.width;
                                    //     let height = data.cell.height;

                                    //     let col1Width = width * 0.4;
                                    //     let col2Width = width * 0.6;

                                    //     // Draw outer rectangle (main box)
                                    //     doc.setDrawColor(0, 0, 0); 
                                    //     doc.setLineWidth(0.3);
                                    //     doc.rect(x, y+0.3, (width - 0.5), 3); 

                                    // }
                                    if (show == 1) {

                                        // signature
                                        if (data.row.index === 9 && signatureChecker) {
                                            const textPos = data.cell;
                                            doc.addImage(
                                                signatureChecker,
                                                'PNG',
                                                textPos.x + 22,
                                                textPos.y - 8,
                                                imageSignatureWidth, // width
                                                imageSignatureHeight  // height
                                            )
                                            if (res.items.row.dateCheckedSign)  {
                                                lines = [
                                                    'Digitally signed by',
                                                    res.items.row.checkedBySign,
                                                    res.items.row.dateCheckedSign + ' +0800'
                                                ]

                                                const lineHeight = 2.8
                                                doc.setFontSize(8)
                                                doc.setFont('helvetica', 'normal')
                                                lines.forEach((line, i) => {
                                                    doc.text(line, textX, textY + i * lineHeight, { baseline: 'middle' })
                                                })
                                            }
                                        }
                                    }
                                }
                            })
                            tbl1Height = (doc.autoTable.previous.finalY - y)
                            doc.autoTable({
                                html: '#tableBody26', 
                                theme: 'plain', 
                                startY: y+1,  
                                margin: { left: tables22MarginLeft, right: tables22MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles2, 
                                tableLineWidth: 0.3, 
                                tableLineColor: [0, 0, 0], 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([4].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top+7.8, bottom: defaultPadding.bottom }
                                    }
                                    if ([5].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom + 2 }
                                    }
                                    if ([6].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 9
                                        data.cell.styles.fontStyle = 'bold'
                                    }
                                    if ([6,7,8].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                    }
                                    if ([7].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                        data.cell.styles.fontStyle = 'italic'
                                    }

                                    // border
                                    if ([7].includes(data.row.index)) {
                                        if ([4].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([4].includes(data.row.index)) {
                                        if ([10].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }

                                },
                                didDrawCell: function (data) {

                                    const textPos = data.cell 
                                    const textX = textPos.x + 1 
                                    const textY = textPos.y + 0.5 

                                    if ([2,3].includes(data.row.index) & data.column.index === 4) {
                                        let doc = data.doc;
                                        let x = data.cell.x;
                                        let y = data.cell.y;
                                        let width = data.cell.width;
                                        let height = data.cell.height;

                                        // Draw outer rectangle (main box)
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y+0.3, (width - 0.5), 3) 

                                        if (res.items.row.dateRecommended != '' && data.row.index === 2 && data.column.index === 4) doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        if (res.items.row.dateRecommended == '' && res.items.row.status < 0 && res.items.row.recommender==res.items.row.disapprover && data.row.index === 3 && data.column.index === 4) {
                                            doc.addImage(imageCheck, 'PNG', textX, textY, imageCheckSize, imageCheckSize)
                                        }

                                    }
                                    if (show == 1) {

                                        const textPos = data.cell;
                                        const textX = textPos.x + imageSignatureWidth + 6 
                                        const textY = textPos.y - 6.5

                                        // signature
                                        if (data.row.index === 6 && data.column.index === 4 && signatureRecommender) {
                                            const textPos = data.cell;
                                            doc.addImage(
                                                signatureRecommender,
                                                'PNG',
                                                textPos.x + 5,
                                                textPos.y - 8,
                                                imageSignatureWidth, // width
                                                imageSignatureHeight  // height
                                            )
                                            if (res.items.row.dateRecommendedSign)  {
                                                lines = [
                                                    'Digitally signed by',
                                                    res.items.row.recommendedBySign,
                                                    res.items.row.dateRecommendedSign + ' +0800'
                                                ]

                                                const lineHeight = 2.8
                                                doc.setFontSize(8)
                                                doc.setFont('helvetica', 'normal')
                                                lines.forEach((line, i) => {
                                                    doc.text(line, textX, textY + i * lineHeight, { baseline: 'middle' })
                                                })
                                            }
                                        }
                                    }
                                }
                            })
                            tbl2Height = (doc.autoTable.previous.finalY - y)

                            tblHeight = tbl1Height > tbl2Height ? tbl1Height : tbl2Height

                            yEnd += tblHeight 
                            y += tblHeight 
                            // 

                            // rectangle
                            doc.rect(inches_1/2 - 0.5, yStart, widthWithoutMargin+1, yEnd+1)

                            y += 1

                            doc.autoTable({
                                html: '#tableBodyLast1', 
                                theme: 'plain', 
                                startY: y,  
                                margin: { left: tables1MarginLeft, right: tables1MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'bottom'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if (data.row.index > 0) {
                                        if ([6,56].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                    }
                                    if ([6].includes(data.column.index)) {
                                        data.cell.styles.halign = 'center'
                                    }

                                },
                            })

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 8

                            doc.autoTable({
                                html: '#tableBodyLast2', 
                                theme: 'plain', 
                                startY: y,  
                                margin: { left: tables1MarginLeft, right: tables1MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.2, bottom: 0.2 }

                                    data.cell.styles.fontSize       = 8
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'bottom'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([0].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 10
                                        data.cell.styles.fontStyle = 'bold'
                                    }
                                    if ([1].includes(data.row.index)) {
                                        if ([30].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
                                        data.cell.styles.fontStyle = 'italic'
                                    }
                                    if ([2].includes(data.row.index)) {
                                        data.cell.styles.fontStyle = 'italic'
                                    }
                                },
                                didDrawCell: function (data) {
                                    if (show == 1) {
                                        const textPos = data.cell;
                                        const textX = textPos.x + imageSignatureWidth + 1 
                                        const textY = textPos.y - 6.5
                                        // signature
                                        if (data.row.index === 0 && data.column.index === 30 && signatureApprover) {
                                            const textPos = data.cell;
                                            doc.addImage(
                                                signatureApprover,
                                                'PNG',
                                                textPos.x,
                                                textPos.y - 8,
                                                imageSignatureWidth, // width
                                                imageSignatureHeight  // height
                                            )
                                            if (res.items.row.dateApprovedSign)  {
                                                lines = [
                                                    'Digitally signed by',
                                                    res.items.row.approvedBySign,
                                                    res.items.row.dateApprovedSign + ' +0800'
                                                ]

                                                const lineHeight = 2.8
                                                doc.setFontSize(8)
                                                doc.setFont('helvetica', 'normal')
                                                lines.forEach((line, i) => {
                                                    doc.text(line, textX, textY + i * lineHeight, { baseline: 'middle' })
                                                })
                                            }
                                        }
                                    }
                                }
                            })
    
                            await generateQrcode(doc, `${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`, dY)
                            generatePdfFile(doc)

                        } else if (res.status == 401 && res.message == 'Invalid token') {
                            authenticationLogout()
                        } else {

                            width   = doc.internal.pageSize.getWidth()
                            height  = doc.internal.pageSize.getHeight()
                            
                            doc.setFont('helvetica', 'italic')
                            doc.setFontSize(42)
                            doc.setTextColor(255, 0, 0)
                            
                            text = `ACCESS DENIED`
                            textWidth = doc.getTextWidth(text)
                            doc.text(text,((width/2) - (textWidth/2)), (height/2) - 50)    

                            generatePdfFile(doc)

                        }
                    })()

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        } 

        function generatePdfFile(doc)
        {

            // preview
            document.getElementById('main-iframe').setAttribute('src', doc.output('bloburl'))
            // download if mobile app
            if (/Mobi|Android/i.test(navigator.userAgent)) { 
                const blob = doc.output('blob')
                const url = URL.createObjectURL(blob)
                window.open(url, '_blank') 
            }

        } 

        (function() {
            generatePDF()
        })() 

    </script>
@endsection