@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="tableHeader">
        <tr>
            <td rowspan="1">Control Number</td>
            <td rowspan="1">Name</td>
            <td rowspan="1">Date of Travel</td>
            <td rowspan="1">Destination</td>
            <td rowspan="1">Purpose</td>
        </tr>

        <tr>
            <td rowspan="1">12345</td>
            <td rowspan="1">DEMATA, ROGINCEL TRIMIDAL</td>
            <td rowspan="1">01/06/97 08:12 am</td>
            <td rowspan="1">This is the destination details</td>
            <td rowspan="1">This is the purpose details</td>
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
        <tr>
            <td rowspan="1"></td>
            <td rowspan="1"></td>
            <td rowspan="1"></td>
            <td rowspan="1"></td>
            <td rowspan="1"></td>
        </tr>

    </table>

@endsection

@section('scripts')
    <script>
        
        let docTitle = "Travel Report"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let widthPage = 0
        let widthWithoutMargin = 0
        let y = (inches_1/5)*1

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

        // items
        function getItems(doc, widthPage)
        {

            apiCall(`/api/{{ "$controller" }}/print-pds-data/0/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        tables1MarginLeft  = (inches_1/5)*1
                        tables1MarginRight = (inches_1/5)*1
    
                        numColumns = 100
                        columnWidth = widthWithoutMargin / numColumns

                        columnStyles = {}
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles[i] = { cellWidth: columnWidth };
                        }

                        /** ****************** PAGE 1 ****************** */
                        

                        // ************* HEADER *************
                        
                        doc.autoTable({
                            html: '#tableHeader', 
                            theme: 'grid', 
                            startY: y,  
                            margin: { left: tables1MarginLeft, right: tables1MarginRight },
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