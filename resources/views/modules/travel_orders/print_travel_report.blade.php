@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader">
        <tr><td colspan="100" rowspan="1">Republic of the Philippines</td></tr>
        <tr><td colspan="100" rowspan="1">Province of Agusan del Sur</td></tr>
        <tr><td colspan="100" rowspan="1">MUNICIPALITY OF TRENTO</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">TRAVEL ORDER</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">Filters</td></tr>
        <tr>
            <td colspan="9" rowspan="1">Date Inserted</td>
            <td colspan="1" rowspan="1">:</td>
            <td colspan="90" rowspan="1" class="filterDateInserted"></td>
        </tr>
        <tr>
            <td colspan="9" rowspan="1">Office(s)</td>
            <td colspan="1" rowspan="1">:</td>
            <td colspan="90" rowspan="1" class="filterOffices"></td>
        </tr>
    </table>
    <table id="tableBody">
        <tr>
            <td rowspan="1">Control Number</td>
            <td rowspan="1">Name</td>
            <td rowspan="1">Date of Travel</td>
            <td rowspan="1">Destination</td>
            <td rowspan="1">Purpose</td>
            <td rowspan="1">Office</td>
            <td rowspan="1">Date Inserted</td>
        </tr>
    </table>
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
        
        let docTitle = "Travel Report (Approved)"
        let headerImage1 = "{{ $headerImage1 }}"
        let headerImage2 = "{{ $headerImage2 }}"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = (inches_1/5)*1
        let imageSize = 16
        let imageOpaqueSize = imageSize * 7
        let marginFromCenter = 44 

        let columnStyles = 0
        let tables1MarginLeft = 0
        let tables1MarginRight = 0

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'l',
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
            widthWithoutMargin = widthPage - ((inches_1/5))*2

            getItems(doc, widthPage)
            return
            
        } 

        async function generateQrcode(doc, url, y)
        {

            doc.addImage(await QRCode.toDataURL(url), 'PNG', 10, 10, 250, 250)

        }

        function generatePageData(data)
        {

            tableID = 'tableHeader'
            document.querySelectorAll(`#${tableID} .filterDateInserted`).forEach(el => el.textContent = data.filterDateInserted)
            document.querySelectorAll(`#${tableID} .filterOffices`).forEach(el => el.textContent = data.filterOffices)
            tableID = 'tableBody'
            if (data.records.length > 0) {
                for (key in data.records) {
                    $(`#${tableID} tbody`).append(`
                        <tr>
                            <td rowspan="1">${data.records[key].code}</td>
                            <td rowspan="1">${data.records[key].employee}</td>
                            <td rowspan="1">${data.records[key].dateTravel}</td>
                            <td rowspan="1">${data.records[key].destination}</td>
                            <td rowspan="1">${data.records[key].purpose}</td>
                            <td rowspan="1">${data.records[key].office}</td>
                            <td rowspan="1">${data.records[key].dateInserted}</td>
                        </tr>
                    `)
                }
            } else {
                $(`#${tableID} tbody`).append(`
                    <tr>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                    </tr>
                    <tr>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                    </tr>
                    <tr>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                        <td rowspan="1"></td>
                    </tr>
                `)
            }
            tableID = 'tableFooter'
            document.querySelectorAll(`#${tableID} .approver`).forEach(el => el.textContent = data.approver)
            document.querySelectorAll(`#${tableID} .approverPos`).forEach(el => el.textContent = data.approverPos)
            document.querySelectorAll(`#${tableID} .preparer`).forEach(el => el.textContent = data.preparer)
            document.querySelectorAll(`#${tableID} .preparerPos`).forEach(el => el.textContent = data.preparerPos)

        }

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-travel-report-data?{!! $qString !!}`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    (async () => {
                        if (res.status == 200) {

                            generatePageData(res.items)

                            tables1MarginLeft  = (inches_1/5)*1
                            tables1MarginRight = (inches_1/5)*1
        
                            numColumns = 100
                            columnWidth = widthWithoutMargin / numColumns

                            columnStyles = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles[i] = { cellWidth: columnWidth };
                            }

                            y += 0.5 
                            dY = y 

                            const qrCodeDataUrl = await QRCode.toDataURL(`${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`)

                            const header = async (data) => { 
                                doc.autoTable({
                                    html: '#tableHeader', 
                                    theme: 'plain', 
                                    startY: dY,  
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

                                        defaultPadding = { left: 0.5, right: 0.5, top: 0.2, bottom: 0.2 }

                                        data.cell.styles.fontSize       = 9
                                        data.cell.styles.fontStyle      = 'normal'
                                        data.cell.styles.valign         = 'top'
                                        data.cell.styles.halign         = 'center'
                                        data.cell.styles.textColor      = [0, 0, 0]
                                        data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                        if ([3].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 8
                                            data.cell.styles.halign = 'right'
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([4].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 11
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([7].includes(data.row.index)) data.cell.styles.halign = 'left'
                                        if ([6,7,8].includes(data.row.index)) {
                                            if ([6].includes(data.row.index) || [10].includes(data.column.index)) data.cell.styles.fontStyle = 'bold'
                                            data.cell.styles.fontSize = 9
                                            data.cell.styles.halign = 'left'
                                        }

                                    },
                                    didDrawCell: () => {}, 
                                })
                                doc.addImage(headerImage1, 'PNG', (widthPage/2)-marginFromCenter, dY, imageSize, imageSize)
                                doc.addImage(headerImage2, 'PNG', ((widthPage/2)-(imageOpaqueSize/2))+0, dY+40, imageOpaqueSize, imageOpaqueSize) 
                                doc.addImage(qrCodeDataUrl, 'PNG', tables1MarginLeft - 1.2, dY-1.2, 20, 20)
                                // await generateQrcode(doc, `${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`, dY)
                            }

                            // ************* BODY *************
                            const tables1MarginTop = 47
                            doc.autoTable({
                                html: '#tableBody', 
                                theme: 'grid', 
                                startY: tables1MarginTop,  
                                margin: { left: tables1MarginLeft, right: tables1MarginRight, top: tables1MarginTop },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                // columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }

                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([0].includes(data.row.index)) {
                                        data.cell.styles.fontStyle = 'bold'
                                    }
                                    if ([1,3,4].includes(data.column.index)) {
                                        data.cell.styles.halign = 'left'
                                    }

                                },
                                didDrawPage: (data) => { 

                                    header(data) 

                                    // Paging (Page X of Y)
                                    const pageSize = doc.internal.pageSize;
                                    const pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
                                    const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();

                                    const pageNumber = doc.internal.getNumberOfPages();
                                    const totalPagesExp = '{total_pages_count_string}';

                                    
                                    doc.setFontSize(8)
                                    doc.setTextColor(100)
                                    text = `Page ${pageNumber}`
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, ((widthPage - tables1MarginRight) - textWidth), pageHeight - 8)
                                },
                            }) 

                            y += (doc.autoTable.previous.finalY - y) 

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