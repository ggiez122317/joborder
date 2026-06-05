@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader1">
        <tr><td colspan="100" rowspan="1">Republic of the Philippines</td></tr>
        <tr><td colspan="100" rowspan="1">Province of Agusan del Sur</td></tr>
        <tr><td colspan="100" rowspan="1">MUNICIPALITY OF TRENTO</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">SERVICE RECORD</td></tr>
        <tr><td colspan="100" rowspan="1">(to be accomplished by employer)</td></tr>
    </table>
    <table id="tableHeader2">
        <tr>
            <td colspan="8" rowspan="1">Name</td>
            <td colspan="18" rowspan="1" class="lname"></td>
            <td colspan="18" rowspan="1" class="fname"></td>
            <td colspan="18" rowspan="1" class="mname"></td>
            <td colspan="38" rowspan="2">(If married woman, give also full maiden name)</td>
        </tr>
        <tr>
            <td colspan="8" rowspan="1"></td>
            <td colspan="18" rowspan="1">(Surname)</td>
            <td colspan="18" rowspan="1">(Given Name)</td>
            <td colspan="18" rowspan="1">(Middle)</td>
        </tr>
        <tr>
            <td colspan="100" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="8" rowspan="1">Birth</td>
            <td colspan="18" rowspan="1" class="birthDate"></td>
            <td colspan="36" rowspan="1" class="address"></td>
            <td colspan="38" rowspan="2">(Date herein should be checked from birth of baptism certificate or some other reliable documents)</td>
        </tr>
        <tr>
            <td colspan="8" rowspan="1"></td>
            <td colspan="18" rowspan="1">(Date)</td>
            <td colspan="36" rowspan="1">(Place)</td>
        </tr>
    </table>
    <table id="tableHeader3">
        <tr><td colspan="100" rowspan="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the employee herein above actually rendered service in this office as shown by the service below, each line of which is supported by appointment and other papers actually issued by this office has approved by the authorities concerned.</td></tr>
    </table>

    <table id="tableBody">
        <tr>
            <td colspan="20" rowspan="1">SERVICE</td>
            <td colspan="35" rowspan="1">RECORD OF APPOINTMENT</td>
            <td colspan="19" rowspan="1">OFFICE/ ENTITY/ DIVISION</td>
            <td colspan="7" rowspan="1">L/V ABS</td>
            <td colspan="19" rowspan="3">SEPARATION/ Cause</td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">(Inclusive Dates)</td>
            <td colspan="13" rowspan="2">Designation</td>
            <td colspan="7" rowspan="2">Status</td>
            <td colspan="15" rowspan="2">Salary</td>
            <td colspan="19" rowspan="2">Station/ Place/ Branch of Assignment</td>
            <td colspan="7" rowspan="2">W/O Pay</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1">From</td>
            <td colspan="10" rowspan="1">To</td>
        </tr>

    </table>

    <table id="tableFooter1">
        <tr><td colspan="100" rowspan="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued in compliance with Executive order No. 54, dated August 10, 1954 and in accordance with circular No. 54, dated August 10, 1954 of the System</td></tr>
    </table>

    <table id="tableFooter2">

        <tr>
            <td colspan="40" rowspan="1">&nbsp;</td>
            <td colspan="60" rowspan="1">CERTIFIED CORRECT:</td>
        </tr>

        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <tr>
            <td colspan="20" rowspan="1" class="date"></td>
            <td colspan="35" rowspan="1">&nbsp;</td>
            <td colspan="45" rowspan="1" class="approver"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Date</td>
            <td colspan="35" rowspan="1">&nbsp;</td>
            <td colspan="45" rowspan="1" class="approverPos"></td>
        </tr>

        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>

        <tr>
            <td colspan="20" rowspan="1">OR No.:</td>
            <td colspan="80" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Issued on:</td>
            <td colspan="80" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Issued at:</td>
            <td colspan="80" rowspan="1">&nbsp;</td>
        </tr>
        
    </table>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        
        let docTitle = "Service Record"
        let headerImage1    = "{{ $headerImage1 }}"
        let headerImage2    = "{{ $headerImage2 }}"
        let imageOpaque     = "{{ $imageOpaque }}"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let imageSize = 16
        let imageOpaqueSize = imageSize * 7
        let widthWithoutMargin = 0
        let y = (inches_1/2)

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'p',
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
            widthWithoutMargin = widthPage - ((inches_1/5))

            getItems(doc, widthPage)
            return
            
        } 

        async function generateQrcode(doc, url, y)
        {

            doc.addImage(await QRCode.toDataURL(url), 'PNG', (inches_1/5)*1.5, y-1.5, 19, 19)

        }

        function generatePageData(data)
        {
            
            tableID = 'tableHeader2'
            document.querySelectorAll(`#${tableID} .lname`).forEach(el => el.textContent = data.items.row.lname)
            document.querySelectorAll(`#${tableID} .fname`).forEach(el => el.textContent = data.items.row.fname)
            document.querySelectorAll(`#${tableID} .mname`).forEach(el => el.textContent = data.items.row.mname)
            document.querySelectorAll(`#${tableID} .birthDate`).forEach(el => el.textContent = data.items.row.birthDate)
            document.querySelectorAll(`#${tableID} .address`).forEach(el => el.textContent = data.items.row.address)

            html = ''
            if (data.items.row.records.length > 0) {
                for (key in data.items.row.records) {
                    html += `
                        <tr>
                            <td colspan="10" rowspan="1">${data.items.row.records[key].dateFrom}</td>
                            <td colspan="10" rowspan="1">${data.items.row.records[key].dateTo}</td>
                            <td colspan="13" rowspan="1">${data.items.row.records[key].designation}</td>
                            <td colspan="7" rowspan="1">${data.items.row.records[key].status}</td>
                            <td colspan="15" rowspan="1">${data.items.row.records[key].salary}</td>
                            <td colspan="19" rowspan="1">${data.items.row.records[key].station}</td>
                            <td colspan="7" rowspan="1">${data.items.row.records[key]['???']}</td>
                            <td colspan="19" rowspan="1">${data.items.row.records[key].clause}</td>
                        </tr>
                    `
                }
            }
            html += `
                <tr>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="13" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="15" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                </tr>
                <tr>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="13" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="15" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                </tr>
                <tr>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="10" rowspan="1"></td>
                    <td colspan="13" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="15" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                    <td colspan="7" rowspan="1"></td>
                    <td colspan="19" rowspan="1"></td>
                </tr> 
            `
            $('#tableBody tbody').append(html)

            tableID = 'tableFooter2'
            document.querySelectorAll(`#${tableID} .date`).forEach(el => el.textContent = data.date)
            document.querySelectorAll(`#${tableID} .approver`).forEach(el => el.textContent = data.approver)
            document.querySelectorAll(`#${tableID} .approverPos`).forEach(el => el.textContent = data.approverPos)

        }

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-service-record-data/{{ $id }}`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    (async () => {
                        if (res.status == 200) {

                            generatePageData(res)

                            tables2CenterMargin = 4
                            tables2Width = (widthWithoutMargin - tables2CenterMargin) / 2

                            tables21MarginLeft  = (inches_1/5)*1.5
                            tables22MarginRight = (inches_1/5)*1.5
        
                            numColumns = 100
                            columnWidth = tables2Width / numColumns

                            columnStyles = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles[i] = { cellWidth: columnWidth };
                            }

                            /** ****************** PAGE 1 ****************** */
                            

                            // ************* HEADER *************
                            
                            y += 8

                            doc.autoTable({
                                html: '#tableHeader1', 
                                theme: 'grid', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }

                                    data.cell.styles.fontSize       = 11
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([0,1,5].includes(data.row.index)) {
                                        data.cell.styles.fontSize = 10
                                    }

                                    if ([2,4].includes(data.row.index)) {
                                        data.cell.styles.fontStyle = 'bold'
                                    }

                                },
                            })

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 8
                            
                            doc.autoTable({
                                html: '#tableHeader2', 
                                theme: 'grid', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    // lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles, 
                                didParseCell: function (data) {
                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.3, bottom: 0.3 }
                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'top'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([8,26,44].includes(data.column.index)) {
                                        data.cell.styles.halign = 'center'
                                        if ([0,3].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 10
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                    }
                                    if ([1,4].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                    }

                                },
                                didDrawCell: function (data) {

                                    let doc         = data.doc;
                                    let cellX       = data.cell.x;
                                    let cellY       = data.cell.y;
                                    let cellWidth   = data.cell.width;
                                    let cellHeight  = data.cell.height;

                                    if ([8,26,44].includes(data.column.index)) {
                                        if ([1,4].includes(data.row.index)) {
                                            marginLine = 0
                                            doc.line(cellX+marginLine, cellY-0.6, cellX+(cellWidth-(marginLine*1)), cellY-0.6)
                                        }
                                    }

                                }

                            })

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 3
                            
                            doc.autoTable({
                                html: '#tableHeader3', 
                                theme: 'grid', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
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
                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                },

                            })

                            // ************* BODY *************

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 2
                            
                            positionX = 0
                            positiony = 0
                            hasNoText = 0
                            doc.autoTable({
                                html: '#tableBody', 
                                theme: 'plain', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
                                styles: { 
                                    font: 'helvetica', 
                                    lineColor: [0, 0, 0], 
                                    lineWidth: 0, 
                                    lineWidth: 0.3, 
                                    textColor: [0, 0, 0], 
                                }, 
                                columnStyles: columnStyles, 
                                didParseCell: function (data) {

                                    defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }
                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'top'
                                    data.cell.styles.halign         = 'center'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if (data.row.index === 0 && data.column.index === 55) data.cell.styles.fontSize = 8
                                    if (data.row.index === 1 && data.column.index === 74) data.cell.styles.fontSize = 8
                                    if (data.row.index > 2) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0.3, bottom: 0, left: 0.3 }
                                    }
                                    if (parseInt(data.row.index) === parseInt(data.table.body.length - 1)) {
                                        data.cell.styles.lineWidth = { top: 0, right: 0.3, bottom: 0.3, left: 0.3 }
                                    }
                                    if ([3].includes(data.row.index)) {
                                        data.cell.styles.lineWidth = { top: 0.3, right: 0.3, bottom: 0, left: 0.3 }
                                    }
                                    if (data.row.index > 2) {
                                        if ([81].includes(data.column.index)) {
                                            data.cell.styles.halign = 'left'
                                        }
                                    }

                                },
                                didDrawCell: function (data) {
                                    if (data.cell.text[0] == '' && data.column.index === 0 && !hasNoText) {
                                        hasNoText = 1
                                        positionX = data.cell.x + 2
                                        positiony = data.cell.y + (data.cell.height / 2) - 1
                                    }
                                }

                            })

                            // 
                            doc.setFontSize(9)
                            doc.text(
                                'XXXXXXXXXXXXX', 
                                positionX,
                                positiony,
                                { baseline: 'middle' }
                            )
                            doc.setFontSize(7)
                            doc.setFont("helvetica", "bold")
                            doc.text(
                                'STILL IN SERVICE', 
                                positionX+161,
                                positiony,
                                { baseline: 'middle' }
                            )
                            doc.setFont("helvetica", "normal")

                            // ************* FOOTER *************

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 1

                            doc.autoTable({
                                html: '#tableFooter1', 
                                theme: 'grid', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
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
                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                },

                            })

                            y += (doc.autoTable.previous.finalY - y) 
                            y += 8 

                            doc.autoTable({
                                html: '#tableFooter2', 
                                theme: 'grid', 
                                startY: y,  
                                margin: { left: tables21MarginLeft, right: tables22MarginRight },
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
                                    data.cell.styles.fontSize       = 9
                                    data.cell.styles.fontStyle      = 'normal'
                                    data.cell.styles.valign         = 'middle'
                                    data.cell.styles.halign         = 'left'
                                    data.cell.styles.textColor      = [0, 0, 0]
                                    data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }

                                    if ([3,4].includes(data.row.index)) {
                                        data.cell.styles.halign = 'center'
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0.3, bottom: 0.3 }
                                        if ([3].includes(data.row.index)) {
                                            if ([55].includes(data.column.index)) {
                                                data.cell.styles.fontStyle = 'bold'
                                            }
                                        }
                                    }
                                    if ([6,7,8].includes(data.row.index)) {
                                        data.cell.styles.halign = 'right'
                                        data.cell.styles.fontSize = 7
                                        data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0.5, bottom: 0.5 }
                                    }

                                },
                                didDrawCell: function (data) {

                                    let doc         = data.doc;
                                    let cellX       = data.cell.x;
                                    let cellY       = data.cell.y;
                                    let cellWidth   = data.cell.width;
                                    let cellHeight  = data.cell.height;

                                    if ([4].includes(data.row.index)) {
                                        if ([0].includes(data.column.index)) {
                                            doc.line(cellX+marginLine, cellY-0.6, cellX+(cellWidth-(marginLine*1)), cellY-0.6)
                                        }
                                    }

                                }

                            })

                            marginFromCenter = 48

                            doc.addImage(headerImage1, 'PNG', (widthPage/2)-marginFromCenter, ((inches_1/5)*1)+15, imageSize, imageSize)
                            doc.addImage(headerImage2, 'PNG', (widthPage/2)+marginFromCenter-imageSize, ((inches_1/5)*1)+15, imageSize+1, imageSize-1)
                            doc.addImage(imageOpaque, 'PNG', ((widthPage/2)-(imageOpaqueSize/2))+0, (((inches_1/5)*1)+15)+90, imageOpaqueSize, imageOpaqueSize) 
                            await generateQrcode(doc, `${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`, ((inches_1/5)*1)+15)

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