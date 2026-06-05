@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader">
        <tr><td colspan="100" rowspan="1">Republic of the Philippines</td></tr>
        <tr><td colspan="100" rowspan="1">Province of Agusan del Sur</td></tr>
        <tr><td colspan="100" rowspan="1">MUNICIPALITY OF TRENTO</td></tr>
        <tr><td colspan="100" rowspan="1" class="code"></td></tr>
        <tr><td colspan="100" rowspan="1">OFFICE OF THE MUNICIPAL MAYOR</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">TRAVEL ORDER</td></tr>
        <tr><td colspan="100" rowspan="1">Personnel Administration</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
    </table>
    <table id="tableBody">
        <tr>
            <td colspan="20" rowspan="1">Name</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="name"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Position</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="position"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Destination</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="destination"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Date</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="date"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Purpose</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="purpose"></td>
        </tr>
        <tr>
            <td colspan="35" rowspan="1">Appropriation to which travel is charged</td>
            <td colspan="3" rowspan="1">:</td>
            <td colspan="62" rowspan="1" class="appropriation"></td>
        </tr>
        <tr>
            <td colspan="20" rowspan="1">Remarks</td>
            <td colspan="5" rowspan="1">:</td>
            <td colspan="75" rowspan="1" class="remarks"></td>
        </tr>
    </table>
    <table id="tableFooter">
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr>
            <td colspan="50" rowspan="1">RECOMMENDED:</td> 
            <td colspan="50" rowspan="1">APPROVED:</td> 
        </tr>
        <tr>
            <td colspan="5" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1">&nbsp;</td>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1"></td>
            <td colspan="5" rowspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1" class="recommenderName">&nbsp;</td>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1" class="approverName"></td>
            <td colspan="5" rowspan="1">&nbsp;</td>
        </tr>
        <!-- 
        <tr> 
            <td colspan="5" rowspan="1">&nbsp;</td> 
            <td colspan="40" rowspan="1" class="dateRecommended">&nbsp;</td> 
            <td colspan="10" rowspan="1">&nbsp;</td> 
            <td colspan="40" rowspan="1" class="dateApproved"></td> 
            <td colspan="5" rowspan="1">&nbsp;</td> 
        </tr> 
        -->
        <tr>
            <td colspan="5" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1" class="recommenderPosition">&nbsp;</td>
            <td colspan="10" rowspan="1">&nbsp;</td>
            <td colspan="40" rowspan="1" class="approverPosition"></td>
            <td colspan="5" rowspan="1">&nbsp;</td>
        </tr>
    </table>

@endsection

@section('scripts') 
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
        
        let docTitle = "Travel Order"
        let headerImage1 = "{{ $headerImage1 }}"
        let headerImage2 = "{{ $headerImage2 }}"
        let show = "{{ $show }}"

        let signatureRecommender    = ''
        let signatureApprover       = ''

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = 0
        let imageSize = 16
        const imageSignatureWidth   = 30
        const imageSignatureHeight  = 10
        let imageOpaqueSize = imageSize * 6
        let marginFromCenter = 44

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
            heightPage = doc.internal.pageSize.getHeight()
            widthWithoutMargin = widthPage - ((inches_1))*2.5
            getItems(doc, widthPage)
            return
            
        } 

        async function generateQrcode(doc, url, y)
        {

            doc.addImage(await QRCode.toDataURL(url), 'PNG', inches_1*1.45, y+11.5, 19, 19)

        }

        function generatePageData(data)
        {

            tableID = 'tableHeader'
            document.querySelectorAll(`#${tableID} .code`).forEach(el => el.textContent = data.row.code)
            tableID = 'tableBody'
            document.querySelectorAll(`#${tableID} .name`).forEach(el => el.textContent = data.row.name)
            document.querySelectorAll(`#${tableID} .position`).forEach(el => el.textContent = data.row.position)
            document.querySelectorAll(`#${tableID} .destination`).forEach(el => el.textContent = data.row.destination)
            document.querySelectorAll(`#${tableID} .date`).forEach(el => el.textContent = data.row.date)
            document.querySelectorAll(`#${tableID} .purpose`).forEach(el => el.textContent = data.row.purpose)
            document.querySelectorAll(`#${tableID} .appropriation`).forEach(el => el.textContent = data.row.appropriation)
            document.querySelectorAll(`#${tableID} .remarks`).forEach(el => el.textContent = data.row.remarks)
            tableID = 'tableFooter'
            document.querySelectorAll(`#${tableID} .recommenderName`).forEach(el => el.textContent = data.row.recommenderName)
            document.querySelectorAll(`#${tableID} .recommenderPosition`).forEach(el => el.textContent = data.row.recommenderPosition)
            document.querySelectorAll(`#${tableID} .approverName`).forEach(el => el.textContent = data.row.approverName)
            document.querySelectorAll(`#${tableID} .approverPosition`).forEach(el => el.textContent = data.row.approverPosition)
            document.querySelectorAll(`#${tableID} .dateRecommended`).forEach(el => el.textContent = data.row.dateRecommended)
            document.querySelectorAll(`#${tableID} .dateApproved`).forEach(el => el.textContent = data.row.dateApproved)

        }

        // items 
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-travel-order-data/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    (async () => {
                        if (res.status == 200) {
    
                            generatePageData(res.items)

                            // signatures
                            signatureRecommender    = res.items.row.signatureRecommender
                            signatureApprover       = res.items.row.signatureApprover
    
                            tables1MarginLeft  = inches_1*1.5
                            tables1MarginRight = inches_1*1
        
                            numColumns = 100
                            columnWidth = widthWithoutMargin / numColumns
    
                            columnStyles = {}
                            for (let i = 0; i < numColumns; i++) {
                                columnStyles[i] = { cellWidth: columnWidth };
                            }
    
                            heightContent = (heightPage-(inches_1*2))/2
                            for (i=0; i<2; i++) {
    
                                
                                if (i==1) y = heightContent
    
                                dY = y
    
                                y += inches_1*0.5
    
                                doc.addImage(headerImage1, 'PNG', (widthPage/2)-marginFromCenter, y, imageSize, imageSize)
                                doc.addImage(headerImage2, 'PNG', ((widthPage/2)-(imageOpaqueSize/2))+8, y+5, imageOpaqueSize, imageOpaqueSize)
    
                                // ************* HEADER *************
                                doc.autoTable({
                                    html: '#tableHeader', 
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
    
                                        if ([3].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 8
                                            data.cell.styles.halign = 'right'
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([4].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 8
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([7].includes(data.row.index)) data.cell.styles.halign = 'left'
                                        if ([6].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 10
                                            data.cell.styles.fontStyle = 'bold'
                                        }
    
                                    },
                                })
    
                                y += (doc.autoTable.previous.finalY - y) 
    
                                // ************* BODY *************
                                doc.autoTable({
                                    html: '#tableBody', 
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
    
                                        defaultPadding = { left: 0.5, right: 0.5, top: 0.5, bottom: 0.5 }
    
                                        data.cell.styles.fontSize       = 8
                                        data.cell.styles.fontStyle      = 'normal'
                                        data.cell.styles.valign         = 'top'
                                        data.cell.styles.halign         = 'start'
                                        data.cell.styles.textColor      = [0, 0, 0]
                                        data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
    
                                        if (data.column.index > 0) data.cell.styles.fontSize = 10
                                        if ([0,2,3,4].includes(data.row.index)) {
                                            if ([25,38].includes(data.column.index)) {
                                                data.cell.styles.fontStyle = 'bold'
                                            }
                                        }
                                        if ([25,38].includes(data.column.index)) {
                                            data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                        }
    
                                    },
                                })
    
                                y += (doc.autoTable.previous.finalY - y) 
    
                                // ************* FOOTER *************
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
    
                                        defaultPadding = { left: 0.5, right: 0.5, top: 0.5, bottom: 0.5 }
    
                                        data.cell.styles.fontSize       = 8
                                        data.cell.styles.fontStyle      = 'normal'
                                        data.cell.styles.valign         = 'top'
                                        data.cell.styles.halign         = 'center'
                                        data.cell.styles.textColor      = [0, 0, 0]
                                        data.cell.styles.cellPadding    = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
    
                                        if (data.row.index === 1) {
                                            data.cell.styles.halign = 'left'
                                            data.cell.styles.fontStyle = 'italic'
                                        }
                                        if ([2,3].includes(data.row.index)) {
                                            data.cell.styles.fontSize = 9
                                            data.cell.styles.fontStyle = 'bold'
                                        }
                                        if ([3].includes(data.row.index)) {
                                            if ([5,55].includes(data.column.index)) {
                                                data.cell.styles.lineWidth = { top: 0, right: 0, bottom: 0.3, left: 0 }
                                            }
                                        }
    
                                    },
                                    didDrawCell: function (data) {
                                        if (show == 1) {

                                            const textPos = data.cell;
                                            const textX = textPos.x + imageSignatureWidth + 1 
                                            const textY = textPos.y - 6.5

                                            // signature
                                            if (data.row.index === 3 && data.column.index === 5 && signatureRecommender) {
                                                const textPos = data.cell;
                                                doc.addImage(
                                                    signatureRecommender,
                                                    'PNG',
                                                    textPos.x,
                                                    textPos.y - 8,
                                                    imageSignatureWidth, // width
                                                    imageSignatureHeight  // height
                                                )
                                                if (res.items.row.dateRecommendedSign)  {
                                                    lines = [
                                                        'Digitally signed by',
                                                        res.items.row.recommenderNameSign,
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
                                            // signature
                                            if (data.row.index === 3 && data.column.index === 55 && signatureApprover) {
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
                                                        res.items.row.approverNameSign,
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
    
                                // dashed line
                                doc.setLineDash([3, 1], 0)
                                doc.line((inches_1*1.5), dY+heightContent, widthPage-(inches_1*1), dY+heightContent) 
                                doc.setLineDash([]) 
    
                                await generateQrcode(doc, `${BASE_URL_BACKEND}/document-checker/view/${res.items.printID}`, dY)

                            }
    
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