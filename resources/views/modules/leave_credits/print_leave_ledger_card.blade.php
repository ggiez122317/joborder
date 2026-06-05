@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader">
        <tr><td colspan="100">EMPLOYEE'S LEAVE LEDGER CARD</td></tr>
    </table>

    <table id="tableBody"></table>
    <table id="tableFooter">
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="30" rowspan="1">Prepared By</td>
            <td colspan="20" rowspan="1"></td>
            <td colspan="30" rowspan="1">Approved By</td>
            <td colspan="10" rowspan="1"></td>
        </tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="30" rowspan="1" class="preparer"></td>
            <td colspan="20" rowspan="1"></td>
            <td colspan="30" rowspan="1" class="approver"></td>
            <td colspan="10" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1"></td>
            <td colspan="30" rowspan="1" class="preparerPos"></td>
            <td colspan="20" rowspan="1"></td>
            <td colspan="30" rowspan="1" class="approverPos"></td>
            <td colspan="10" rowspan="1"></td>
        </tr>
    </table>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        
        let docTitle        = "Leave Ledger Card"
        let headerImage1    = "{{ $headerImage1 }}"
        let headerImage2    = "{{ $headerImage2 }}"
        let imageOpaque     = "{{ $imageOpaque }}"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = (inches_1/5)*1.5
        let imageSize = 16
        let imageOpaqueSize = imageSize * 7
        let marginFromCenter = 75
        let isFirstPage = 1

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'l',
                unit: 'mm',
                format: 'Legal',
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
            widthWithoutMargin = widthPage - (inches_1/5)

            getItems(doc, widthPage)
            return
            
        } 

        async function generateQrcode(doc, url, y)
        {

            doc.addImage(await QRCode.toDataURL(url), 'PNG', 10, 10, 250, 250)

        }

        function drawPageHeader(doc, dy1, dy2, xImg1, xImg2, columnStyles, tables2MarginLeft, tables2MarginRight, qrCodeDataUrl) 
        {

            doc.autoTable({
                html: '#tableHeader', 
                theme: 'grid', 
                startY: dy2,  
                margin: { left: tables2MarginLeft, right: tables2MarginRight },
                styles: { 
                    font: 'helvetica', 
                    lineColor: [0, 0, 0], 
                    lineWidth: 0, 
                    // lineWidth: 0.3, 
                    textColor: [0, 0, 0], 
                }, 
                columnStyles: columnStyles, 
                didParseCell: function (data) {

                    defaultPadding = { left: 0.8, right: 0.8, top: 1, bottom: 5 }

                    data.cell.styles.fontSize       = 16
                    data.cell.styles.fontStyle      = 'bold'
                    data.cell.styles.valign         = 'middle'
                    data.cell.styles.halign         = 'center'
                    data.cell.styles.textColor      = [0, 0, 0]
                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                },
            })

            doc.addImage(headerImage1, 'PNG', xImg1, dy1, imageSize, imageSize)
            doc.addImage(headerImage2, 'PNG', xImg2, dy1, imageSize, imageSize)
            doc.addImage(imageOpaque, 'PNG', ((widthPage/2)-(imageOpaqueSize/2))+0, dy1+40, imageOpaqueSize, imageOpaqueSize) 
            doc.addImage(qrCodeDataUrl, 'PNG', tables2MarginLeft - 1.2, dy2-7, 20, 20)

        }

        function generateData(data)
        {

            // $('#tableBody').html(`
            //     <tr>
            //         <td colspan="9" rowspan="1">NAME:</td>
            //         <td colspan="18" rowspan="1">${data.name}</td>
            //         <td colspan="9" rowspan="1">DIVISION/ OFFICE:</td>
            //         <td colspan="16" rowspan="1">${data.office}</td>
            //         <td colspan="23" rowspan="1">1st DAY in GOVERNMENT SERVICE:</td>
            //         <td colspan="25" rowspan="1">${data.dateAppointed}</td>
            //     </tr>
            //     <tr>
            //         <td colspan="9" rowspan="1">Present Position:</td>
            //         <td colspan="18" rowspan="1">${data.position}</td>
            //         <td colspan="32" rowspan="1"></td>
            //         <td colspan="16" rowspan="1">Present Salary Rate:</td>
            //         <td colspan="25" rowspan="1">${data.salary}</td>
            //     </tr>
            //     <tr>
            //         <td colspan="100" rowspan="1">&nbsp;</td>
            //     </tr>
            //     <tr>
            //         <td colspan="14" rowspan="2">PERIOD</td>
            //         <td colspan="13" rowspan="2">PARTICULARS</td>
            //         <td colspan="32" rowspan="1">VACATION LEAVE</td>
            //         <td colspan="32" rowspan="1">SICK LEAVE</td>
            //         <td colspan="9" rowspan="2">DATE & ACTION TAKEN APPLICATION FOR LEAVE</td>
            //     </tr>
            //     <tr>
            //         <td colspan="9" rowspan="1">EARNED</td>
            //         <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/PAY</td>
            //         <td colspan="9" rowspan="1">BALANCE</td>
            //         <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/O PAY</td>
            //         <td colspan="9" rowspan="1">EARNED</td>
            //         <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/PAY</td>
            //         <td colspan="9" rowspan="1">BALANCE</td>
            //         <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/O PAY</td>
            //     </tr>
            // `)

            // if (data.records.length > 0) {
            //     for (key2 in data.records) {
            //         $('#tableBody').append(`
            //             <tr>
            //                 <td colspan="14" rowspan="1">${data.records[key2].period}</td>
            //                 <td colspan="13" rowspan="1">${data.records[key2].particulars}</td>
            //                 <td colspan="9" rowspan="1">${data.records[key2].vacationEarned}</td>
            //                 <td colspan="7" rowspan="1">${data.records[key2].vacationUndertimeWithPay}</td>
            //                 <td colspan="9" rowspan="1">${data.records[key2].vacationBalance}</td>
            //                 <td colspan="7" rowspan="1">${data.records[key2].vacationUndertimeWithoutPay}</td>
            //                 <td colspan="9" rowspan="1">${data.records[key2].sickEarned}</td>
            //                 <td colspan="7" rowspan="1">${data.records[key2].sickUndertimeWithPay}</td>
            //                 <td colspan="9" rowspan="1">${data.records[key2].sickBalance}</td>
            //                 <td colspan="7" rowspan="1">${data.records[key2].sickUndertimeWithoutPay}</td>
            //                 <td colspan="9" rowspan="1">${data.records[key2].remarks}</td>
            //             </tr>
            //         `)
            //     }
            // }

            // $('#tableBody').append(`
            //     <tr>
            //         <td colspan="14" rowspan="1"></td>
            //         <td colspan="13" rowspan="1"></td>
            //         <td colspan="9" rowspan="1"></td>
            //         <td colspan="7" rowspan="1"></td>
            //         <td colspan="9" rowspan="1"></td>
            //         <td colspan="7" rowspan="1"></td>
            //         <td colspan="9" rowspan="1"></td>
            //         <td colspan="7" rowspan="1"></td>
            //         <td colspan="9" rowspan="1"></td>
            //         <td colspan="7" rowspan="1"></td>
            //         <td colspan="9" rowspan="1"></td>
            //     </tr>
            // `)
            tableID = 'tableFooter'
            document.querySelectorAll(`#${tableID} .approver`).forEach(el => el.textContent = data.approver)
            document.querySelectorAll(`#${tableID} .approverPos`).forEach(el => el.textContent = data.approverPos)
            document.querySelectorAll(`#${tableID} .preparer`).forEach(el => el.textContent = data.preparer)
            document.querySelectorAll(`#${tableID} .preparerPos`).forEach(el => el.textContent = data.preparerPos)

        }

        function recursivePage(data, records, doc, tables2MarginLeft, tables2MarginRight, columnStyles, dy1, dy2, xImg1, xImg2, yTable=0, qrCodeDataUrl)
        {

            const numOfRows = 20

            let arrayOrig = records
            let arrayRows = arrayOrig.splice(0, numOfRows)

            if (arrayRows.length > 0) {
                $('#tableBody').html(`
                    <tr>
                        <td colspan="9" rowspan="1">NAME:</td>
                        <td colspan="18" rowspan="1">${data.name}</td>
                        <td colspan="9" rowspan="1">DIVISION/ OFFICE:</td>
                        <td colspan="16" rowspan="1">${data.office}</td>
                        <td colspan="23" rowspan="1">1st DAY in GOVERNMENT SERVICE:</td>
                        <td colspan="25" rowspan="1">${data.dateAppointed}</td>
                    </tr>
                    <tr>
                        <td colspan="9" rowspan="1">Present Position:</td>
                        <td colspan="18" rowspan="1">${data.position}</td>
                        <td colspan="32" rowspan="1"></td>
                        <td colspan="16" rowspan="1">Present Salary Rate:</td>
                        <td colspan="25" rowspan="1">${data.salary}</td>
                    </tr>
                    <tr>
                        <td colspan="100" rowspan="1">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="14" rowspan="2">PERIOD</td>
                        <td colspan="13" rowspan="2">PARTICULARS</td>
                        <td colspan="32" rowspan="1">VACATION LEAVE</td>
                        <td colspan="32" rowspan="1">SICK LEAVE</td>
                        <td colspan="9" rowspan="2">DATE & ACTION TAKEN APPLICATION FOR LEAVE</td>
                    </tr>
                    <tr>
                        <td colspan="9" rowspan="1">EARNED</td>
                        <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/PAY</td>
                        <td colspan="9" rowspan="1">BALANCE</td>
                        <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/O PAY</td>
                        <td colspan="9" rowspan="1">EARNED</td>
                        <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/PAY</td>
                        <td colspan="9" rowspan="1">BALANCE</td>
                        <td colspan="7" rowspan="1">ABSENCE UNDERTIME W/O PAY</td>
                    </tr>
                `)
                for (key2 in arrayRows) {
                    $('#tableBody').append(`
                        <tr>
                            <td colspan="14" rowspan="1">${arrayRows[key2].period}</td>
                            <td colspan="13" rowspan="1">${arrayRows[key2].particulars}</td>
                            <td colspan="9" rowspan="1">${arrayRows[key2].vacationEarned}</td>
                            <td colspan="7" rowspan="1">${arrayRows[key2].vacationUndertimeWithPay}</td>
                            <td colspan="9" rowspan="1">${arrayRows[key2].vacationBalance}</td>
                            <td colspan="7" rowspan="1">${arrayRows[key2].vacationUndertimeWithoutPay}</td>
                            <td colspan="9" rowspan="1">${arrayRows[key2].sickEarned}</td>
                            <td colspan="7" rowspan="1">${arrayRows[key2].sickUndertimeWithPay}</td>
                            <td colspan="9" rowspan="1">${arrayRows[key2].sickBalance}</td>
                            <td colspan="7" rowspan="1">${arrayRows[key2].sickUndertimeWithoutPay}</td>
                            <td colspan="9" rowspan="1">${arrayRows[key2].remarks}</td>
                        </tr>
                    `)
                }

                // print page 
                if (!isFirstPage) doc.addPage()
                if (isFirstPage) isFirstPage = 0

                doc.autoTable({
                    html: '#tableBody', 
                    theme: 'plain', 
                    startY: y,  
                    margin: { 
                        top: 28,
                        left: tables2MarginLeft, 
                        right: tables2MarginRight 
                    },
                    styles: { 
                        font: 'helvetica', 
                        lineColor: [0, 0, 0], 
                        lineWidth: 0, 
                        // lineWidth: 0.3, 
                        textColor: [0, 0, 0], 
                    }, 
                    tableLineWidth: 0.3, 
                    tableLineColor: [0, 0, 0], 
                    columnStyles: columnStyles, 
                    didParseCell: function (data) {

                        defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }

                        data.cell.styles.fontSize       = 9
                        data.cell.styles.fontStyle      = 'normal'
                        data.cell.styles.valign         = 'middle'
                        data.cell.styles.halign         = 'center'
                        data.cell.styles.textColor      = [0, 0, 0]
                        data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                        
                        
                        if (
                            (data.row.index == 0 && data.column.index == 9) || 
                            (data.row.index == 0 && data.column.index == 36) || 
                            (data.row.index == 0 && data.column.index == 75) || 
                            (data.row.index == 1 && data.column.index == 9) || 
                            (data.row.index == 1 && data.column.index == 75)
                        ) {
                            data.cell.styles.fontSize   = 9.5
                            data.cell.styles.fontStyle  = 'bold'
                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                        } 
                        if (data.row.index < 2) {
                            data.cell.styles.halign = 'left'
                        } 
                        if (data.row.index > 2) {
                            data.cell.styles.lineWidth = 0.3
                        } else {
                            data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 1, bottom: 1 }
                            if (data.row.index == 2) data.cell.styles.cellPadding = { left: 0, right: 0, top: 0, bottom: 0 }
                        } 

                    }, 
                    didDrawPage: function (data) {
                        drawPageHeader(doc, dy1, dy2, xImg1, xImg2, columnStyles, tables2MarginLeft, tables2MarginRight, qrCodeDataUrl)
                    },
                })

                yTable = (doc.autoTable.previous.finalY-y)
                return recursivePage(data, arrayOrig, doc, tables2MarginLeft, tables2MarginRight, columnStyles, dy1, dy2, xImg1, xImg2, yTable, qrCodeDataUrl)
            }
            return yTable

        }

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-leave-ledger-card-data/{{ $id }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    (async () => {
                        if (res.status == 200) {

                            generateData(res.items)

                            tables2CenterMargin = 4
                            tables2Width = (widthWithoutMargin - tables2CenterMargin) / 2

                            tables2MarginLeft  = (inches_1/5)*1.5
                            tables2MarginRight = (inches_1/5)*1.5
        
                            numColumns = 100
                            columnWidth = tables2Width / numColumns

                            columnStyles = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles[i] = { cellWidth: columnWidth };
                            }

                            dy1 = y

                            y += 5
                            dy2 = y

                            y += 15

                            const qrCodeDataUrl = await QRCode.toDataURL(`${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`)

                            /** ****************** PAGE 1 ****************** */
                            previous_finalY = 0
                            if (res.items.pages.length > 0) {
                                for (key in res.items.pages) {
                                    previous_finalY = recursivePage(res.items.pages[key], res.items.pages[key].records, doc, tables2MarginLeft, tables2MarginRight, columnStyles, dy1, dy2, ((widthPage/2)-marginFromCenter), (widthPage/2)+marginFromCenter-((inches_1/5)*3), 0, qrCodeDataUrl)
                                    drawPageHeader(doc, dy1, dy2, ((widthPage/2)-marginFromCenter), (widthPage/2)+marginFromCenter-((inches_1/5)*3), columnStyles, tables2MarginLeft, tables2MarginRight, qrCodeDataUrl)
                                }
                            }
                            y += previous_finalY

                            y += 3

                            doc.setFont('helvetica', 'italic')
                            doc.setFontSize(6)
                            doc.setTextColor(100)
                            text = `******`
                            textWidth = doc.getTextWidth(text)
                            doc.text(text, ((widthPage/2) - (textWidth/2)), y)

                            y += 6

                            doc.autoTable({
                                html: '#tableFooter', 
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
                                columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.5, right: 0.5, top: 0.2, bottom: 0.2 }

                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'top'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    // if ([3].includes(data.row.index)) {
                                    //     data.cell.styles.fontSize = 8
                                    //     data.cell.styles.halign = 'right'
                                    //     data.cell.styles.fontStyle = 'bold'
                                    // }
                                    if ([0].includes(data.row.index)) data.cell.styles.halign = 'left'
                                    if ([1].includes(data.row.index)) {
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top + 6, bottom: defaultPadding.bottom }
                                    }
                                    if ([2].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 10
                                        data.cell.styles.fontStyle = 'bold' 
                                        if ([10,60].includes(data.column.index)) data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                    }
                                    if ([3].includes(data.row.index)) {
                                        data.cell.styles.fontStyle = 'italic'
                                    }

                                },
                            }); 

                            // generate
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