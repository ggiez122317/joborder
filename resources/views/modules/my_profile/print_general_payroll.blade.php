@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <!-- PAGE 1 -->
    <table id="tablePage1Header">
        <tr><td>MUNICIPALITY OF TRENTO, Agusan del Sur</td></tr>
        <tr><td>GENERAL PAYROLL ON SALARIES</td></tr>
        <tr><td>For the month of December, 2024</td></tr>
        <tr><td>&nbsp;</td></tr>
    </table>
    <table id="tablePage1Body">
        <tr>
            <td></td>
            <td>ID NO.</td>
            <td>NAME</td>
            <td>BASIC RATE</td>
            <td>PERA</td>
            <td>AMOUNT EARNED</td>
            <td>ACCRUED</td>
            <td>TOTAL DEDUCTIONS</td>
            <td>GROSS LESS</td>
            <td>FIRST HALF</td>
            <td>SECOND HALF</td>
        </tr>
        <tr><td colspan="11">ACCOUNTING OFFICE</td></tr>

        <tr>
            <td>1</td>
            <td>12334</td>
            <td>Demata, Rogincel Trimidal</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
            <td>12342</td>
        </tr>
        <tr>
            <td colspan="2">GRAND TOTAL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <!-- PAGE 2 -->
    <table id="tablePage2Body">
        <tr>
            <td rowspan="2"></td>
            <td rowspan="2">ID NO.</td>
            <td rowspan="1" colspan="25">Less: DEDUCTIONS</td>
            <td rowspan="2">TOTAL Deductions</td>
        </tr>
        <tr>
            <td rowspan="1">GSIS PS</td>
            <td rowspan="1">GSIS EHP</td>
            <td rowspan="1">GSIS POLICY</td>
            <td rowspan="1">GSIS SL</td>
            <td rowspan="1">GSIS CPL</td>
            <td rowspan="1">GSIS ECARD</td>
            <td rowspan="1">GSIS EDUC</td>
            <td rowspan="1">GSIS ELA</td>
            <td rowspan="1">GSIS MPL</td>
            <td rowspan="1">GSIS GPAL</td>
            <td rowspan="1">DBP</td>
            <td rowspan="1">CSB</td>
            <td rowspan="1">TAGUM COOP</td>
            <td rowspan="1">Producers Bank</td>
            <td rowspan="1">Tax-2022</td>
            <td rowspan="1">SAFRA GEMC</td>
            <td rowspan="1">TEMCO SL</td>
            <td rowspan="1">TEMCO MUT</td>
            <td rowspan="1">HDMF MPL</td>
            <td rowspan="1">HDMF CAL</td>
            <td rowspan="1">TAX</td>
            <td rowspan="1">HDMF PREM</td>
            <td rowspan="1">HDMF MP2</td>
            <td rowspan="1">MED</td>
            <td rowspan="1">QUEDAN</td>
            <td rowspan="1">TOTAL DEDUCTIONS</td>
        </tr>


        <tr>
            <td rowspan="1">1</td>
            <td rowspan="1">2</td>
            <td rowspan="1">3</td>
            <td rowspan="1">1,202,334</td>
            <td rowspan="1">5</td>
            <td rowspan="1">6</td>
            <td rowspan="1">7</td>
            <td rowspan="1">8</td>
            <td rowspan="1">9</td>
            <td rowspan="1">0</td>
            <td rowspan="1">1</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
        </tr>
        <tr>
            <td rowspan="1">1</td>
            <td rowspan="1">2</td>
            <td rowspan="1">3</td>
            <td rowspan="1">1,202,334</td>
            <td rowspan="1">5</td>
            <td rowspan="1">6</td>
            <td rowspan="1">7</td>
            <td rowspan="1">8</td>
            <td rowspan="1">9</td>
            <td rowspan="1">0</td>
            <td rowspan="1">1</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
        </tr>
        <tr>
            <td colspan="2">GRAND TOTAL</td>
            <td rowspan="1">3</td>
            <td rowspan="1">1,202,334</td>
            <td rowspan="1">5</td>
            <td rowspan="1">6</td>
            <td rowspan="1">7</td>
            <td rowspan="1">8</td>
            <td rowspan="1">9</td>
            <td rowspan="1">0</td>
            <td rowspan="1">1</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">2</td>
            <td rowspan="1">4</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
            <td rowspan="1">3</td>
        </tr>


    </table>
    <!-- A -->
    <table id="tablePage2FooterA">
        <tr>
            <td colspan="10" rowspan="1">A</td>
            <td colspan="16" rowspan="1">CERTIFIED:</td>
            <td colspan="74" rowspan="1">Service duly rendered as stated.</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="60" rowspan="1"></td>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="20" rowspan="3">10/16/1997</td>
        </tr>
        <tr><td colspan="80" rowspan="1">ROGINCEL TRIMIDAL DEMATA</td></tr>
        <tr>
            <td colspan="80" rowspan="1">Human Resource Management Officer III</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Signature over Printer Name</td>
            <td colspan="20" rowspan="2">Date</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Authorized Official</td>
        </tr>
    </table>
    <!-- B -->
    <table id="tablePage2FooterB">
        <tr>
            <td colspan="10" rowspan="1">B</td>
            <td colspan="16" rowspan="1">CERTIFIED:</td>
            <td colspan="74" rowspan="1">Supporting documents complete and proper.</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="60" rowspan="1"></td>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="20" rowspan="3"></td>
        </tr>
        <tr><td colspan="80" rowspan="1">ROGINCEL TRIMIDAL DEMATA</td></tr>
        <tr>
            <td colspan="80" rowspan="1">Human Resource Management Officer III</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Signature over Printer Name</td>
            <td colspan="20" rowspan="2">Date</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Authorized Official</td>
        </tr>
    </table>
    <!-- C -->
    <table id="tablePage2FooterC">
        <tr>
            <td colspan="10" rowspan="1">C</td>
            <td colspan="16" rowspan="1">CERTIFIED:</td>
            <td colspan="74" rowspan="1">Cash available for the purpose.</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">&nbsp;</td>
            <td colspan="20" rowspan="5">&nbsp;</td>
            <td colspan="20" rowspan="3">&nbsp;</td>
        </tr>
        <tr><td colspan="60" rowspan="1">LUZMINDA M. LANUZA</td></tr>
        <tr>
            <td colspan="60" rowspan="1">Municipal Treasurer</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">Signature over Printer Name</td>
            <td colspan="20" rowspan="2">Date</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">&nbsp;</td>
        </tr>
    </table>
    <!-- D -->
    <table id="tablePage2FooterD">
        <tr>
            <td colspan="10" rowspan="1">D</td>
            <td colspan="70" rowspan="1">APPROVED FOR PAYMENT</td>
            <td colspan="20" rowspan="5"></td>
        </tr>
        <tr><td colspan="80" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="80" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="80" rowspan="1">ROGINCEL DEMATA</td></tr>
        <tr>
            <td colspan="80" rowspan="1">Human Resource Management Officer III</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Signature over Printer Name</td>
            <td colspan="20" rowspan="2">Date</td>
        </tr>
        <tr>
            <td colspan="80" rowspan="1">Authorized Official</td>
        </tr>
    </table>
    <!-- E -->
    <table id="tablePage2FooterE">
        <tr>
            <td colspan="10" rowspan="1">E</td>
            <td colspan="16" rowspan="1">CERTIFIED:</td>
            <td colspan="74" rowspan="2">Each employee whose name appears on the payroll has been paid the amount as indicated opposite his/her name.</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="16" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">&nbsp;</td>
            <td colspan="20" rowspan="5"></td>
            <td colspan="20" rowspan="3"></td>
        </tr>
        <tr><td colspan="60" rowspan="1">ROGINCEL DEMATA</td></tr>
        <tr>
            <td colspan="60" rowspan="1">Human Resource Management Officer III</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">Signature over Printer Name</td>
            <td colspan="20" rowspan="2">Date</td>
        </tr>
        <tr>
            <td colspan="60" rowspan="1">Authorized Official</td>
        </tr>
    </table>
    <!-- F -->
    <table id="tablePage2FooterF">
        <tr>
            <td colspan="10" rowspan="1">F</td>
            <td colspan="90" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="10" rowspan="6"></td>
            <td colspan="90" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="16" rowspan="1">CAFOA No.:</td>
            <td colspan="19" rowspan="1"></td>
            <td colspan="65" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="8" rowspan="1">Date:</td>
            <td colspan="27" rowspan="1"></td>
            <td colspan="65" rowspan="1"></td>
        </tr>
        <tr><td colspan="90" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="90" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="90" rowspan="1">&nbsp;</td></tr>
    </table>

    <!-- prepared by -->
    <table id="tablePage2FooterPreparedBy">
        <tr>
            <td colspan="100" rowspan="1">Prepared By</td>
        </tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr>
            <td colspan="20" rowspan="1">&nbsp;</td>
            <td colspan="60" rowspan="1">ROGINCEL TRIMIDAL DEMATA</td>
            <td colspan="20" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="100" rowspan="1">Payroll incharge</td>
        </tr>
    </table>

@endsection

@section('scripts')
    <script>
        
        let docTitle = "DTR"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = (inches_1/5)*1.5

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'l',
                unit: 'mm',
                format: 'legal',
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
            widthWithoutMargin = widthPage - ((inches_1/5)*2)

            getItems(doc, widthPage)
            return
            
        } 

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-pds-data/0/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        tables2CenterMargin = 4
                        tables2Width = (widthWithoutMargin - tables2CenterMargin) / 2

                        tables4Cols     = 22
                        tables4Width    = widthWithoutMargin/tables4Cols

                        tables2MarginLeft   = (inches_1/5)*1
                        tables2MarginRight  = (inches_1/5)*1

                        tables41MarginLeft  = ((inches_1/5)*1)
                        tables41MarginRight = ((inches_1/5)*1) + (tables4Width*((Math.floor(tables4Cols/3)*2)+1))
                        tables42MarginLeft  = ((inches_1/5)*1) + (tables4Width*Math.floor(tables4Cols/3)) + 5.5
                        tables42MarginRight = ((inches_1/5)*1) + (tables4Width*((Math.floor(tables4Cols/3)*1)+1)) - 5.5
                        tables43MarginLeft  = ((inches_1/5)*1) + (tables4Width*((Math.floor(tables4Cols/3)*2)+1)) - 5
                        tables43MarginRight = ((inches_1/5)*1)
                        
                        numColumns = 100

                        columnWidth = tables2Width / numColumns
                        columnStyles = {}
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles[i] = { cellWidth: columnWidth };
                        }
                        
                        columnWidth4 = (tables4Width*Math.floor(tables4Cols/3)) / numColumns
                        columnStyles4 = {}
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles4[i] = { cellWidth: columnWidth4 };
                        }

                        /** ****************** PAGE 1 ****************** */

                        // ************* BODY *************
                        
                        doc.autoTable({
                            html: '#tablePage1Header', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables2MarginLeft, right: tables2MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0, bottom: 0.3 }

                                data.cell.styles.fontSize       = 10
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                // if (data.row.index === 1) {
                                //     data.cell.styles.halign = 'left'
                                //     data.cell.styles.fontStyle = 'bold'
                                // }
                                // if (data.column.index === 2) {
                                //     data.cell.styles.halign = 'left'
                                // }

                            },
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        
                        doc.autoTable({
                            html: '#tablePage1Body', 
                            theme: 'grid', 
                            startY: y,  
                            margin: { left: tables2MarginLeft, right: tables2MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }

                                data.cell.styles.fontSize       = 9
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if (data.row.index === 1) {
                                    data.cell.styles.halign = 'left'
                                    data.cell.styles.fontStyle = 'bold'
                                }
                                if (data.column.index === 2) {
                                    data.cell.styles.halign = 'left'
                                }

                            },
                        })
                        
                        /** ****************** PAGE 2 ****************** */
                        doc.addPage();

                        // ************* BODY *************

                        doc.autoTable({
                            html: '#tablePage2Body', 
                            theme: 'grid', 
                            startY: y,  
                            margin: { left: tables2MarginLeft, right: tables2MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }

                                data.cell.styles.fontSize       = 9
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if (data.row.index >= 2) {
                                    if (data.column.index === 0) {
                                        data.cell.styles.whiteSpace = "nowrap"
                                    }
                                    if (data.column.index > 2) {
                                        data.cell.styles.fontSize = 8
                                    }
                                }

                                if (data.column.index === 0) {
                                    data.cell.styles.fontSize = 8
                                    data.cell.styles.whiteSpace = "nowrap"
                                }
                                if ([0,1].includes(data.row.index)) {
                                    data.cell.styles.fontSize = 7.5
                                    data.cell.styles.whiteSpace = "nowrap"
                                }

                            },
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        y += 5.5

                        // ************* FOOTER *************

                        doc.autoTable({
                            html: '#tablePage2FooterA', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables41MarginLeft, right: tables41MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([1,3].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }

                                if ([1].includes(data.row.index)) {
                                    if ([80].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.valign = 'bottom'
                                    }
                                }

                                if ([2].includes(data.row.index)) {
                                    data.cell.styles.fontStyle = 'bold'
                                }

                                if ([3].includes(data.row.index)) {
                                    data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                if (data.row.index === (data.table.body.length - 1)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                }

                            },
                        })
                        doc.autoTable({
                            html: '#tablePage2FooterB', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables42MarginLeft, right: tables42MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([1,3].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }

                                if ([1].includes(data.row.index)) {
                                    if ([80].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.valign = 'bottom'
                                    }
                                }

                                if ([2].includes(data.row.index)) {
                                    data.cell.styles.fontStyle = 'bold'
                                }

                                if ([3].includes(data.row.index)) {
                                    data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                if (data.row.index === (data.table.body.length - 1)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                }

                            },
                        })
                        doc.autoTable({
                            html: '#tablePage2FooterC', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables43MarginLeft, right: tables43MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([1,3].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }

                                if ([1].includes(data.row.index)) {
                                    if ([80].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.valign = 'bottom'
                                    }
                                }

                                if ([2].includes(data.row.index)) {
                                    data.cell.styles.fontStyle = 'bold'
                                }

                                if ([3].includes(data.row.index)) {
                                    data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                if (data.row.index === (data.table.body.length - 1)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                }

                            },
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        y += 5.5

                        doc.autoTable({
                            html: '#tablePage2FooterD', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables41MarginLeft, right: tables41MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([2,4].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([80].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.valign = 'bottom'
                                    }
                                }

                                if ([3].includes(data.row.index)) {
                                    data.cell.styles.fontStyle = 'bold'
                                }

                                if ([4].includes(data.row.index)) {
                                    data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                if (data.row.index === (data.table.body.length - 1)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                }

                            },
                        })
                        doc.autoTable({
                            html: '#tablePage2FooterE', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables42MarginLeft, right: tables42MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([1,3].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }

                                if ([2].includes(data.row.index)) {
                                    if ([80].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        data.cell.styles.valign = 'bottom'
                                    }
                                }

                                if ([3].includes(data.row.index)) {
                                    data.cell.styles.fontStyle = 'bold'
                                }

                                if ([4].includes(data.row.index)) {
                                    data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                if (data.row.index === (data.table.body.length - 1)) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                }

                            },
                        })
                        doc.autoTable({
                            html: '#tablePage2FooterF', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables43MarginLeft, right: tables43MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            tableLineWidth: 0.5, 
                            tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                if ([0].includes(data.row.index)) {
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                    if (data.column.index > 0) data.cell.styles.halign = 'left'
                                }

                                if ([2,3].includes(data.row.index)) {
                                    if ([26,18].includes(data.column.index)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                    }
                                }

                            },
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        y += 5.5
                        
                        doc.autoTable({
                            html: '#tablePage2FooterPreparedBy', 
                            theme: 'plain', 
                            startY: y,  
                            margin: { left: tables41MarginLeft, right: tables41MarginRight },
                            styles: { 
                                font: 'helvetica', 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0, 
                                // lineWidth: 0.3, 
                                textColor: [0, 0, 0], 
                            }, 
                            columnStyles: columnStyles4,
                            // tableLineWidth: 0.5, 
                            // tableLineColor: [0, 0, 0],  
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.3 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'middle'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                // if ([2,4].includes(data.row.index)) {
                                //     data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                // }

                                // if ([0].includes(data.row.index)) {
                                //     if ([80].includes(data.column.index)) {
                                //         data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                //         data.cell.styles.valign = 'bottom'
                                //     }
                                // }

                                if ([0].includes(data.row.index)) {
                                    data.cell.styles.halign = 'left'
                                }

                                if ([2].includes(data.row.index)) {
                                    if ([20].includes(data.column.index)) {
                                        data.cell.styles.fontStyle = 'bold'
                                        data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                    }
                                }

                                // if ([0].includes(data.row.index)) {
                                //     if ([0].includes(data.column.index)) {
                                //         data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0.3, left: 0.3 }
                                //     }
                                //     if ([10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                //     if (data.column.index > 0) data.cell.styles.halign = 'left'
                                // }

                                // if (data.row.index === (data.table.body.length - 2)) data.cell.styles.valign = 'top'
                                // if (data.row.index === (data.table.body.length - 1)) {
                                //     data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0, bottom: defaultPadding.bottom }
                                // }

                            },
                        })

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