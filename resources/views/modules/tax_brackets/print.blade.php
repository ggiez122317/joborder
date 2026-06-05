@extends('layouts.print') 

@section('title', $title) 

@section('scripts')
    <script>

        let docTitle    = "{{ $title }} LIST"
        let headerImage = "{{ $headerImage }}"
        let filters = JSON.parse(@json($filters))

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        const headerImageHeight = 17
        let y = inches_1/2

        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'A4',
                putOnlyUsedFonts:true
            })
            doc.setProperties({
                title       : `${getCurrentDateTime()}_${docTitle.split(' ').join('_')}`,
                subject     : '...',
                author      : 'Rogincel Demata',
                keywords    : 'jsPDF, PDF, example',
                creator     : 'DeTech'
            })
            var pageWidth = doc.internal.pageSize.getWidth()

            getItems(doc, pageWidth)
            return
            
        }

        function getItems(doc, pageWidth)
        {

            const formID = 'formIndex'
            apiCall(`/api/{{ "$controller" }}/print-items/?{!! request()->getQueryString() !!}`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                                    
                        y += headerImageHeight + 8
                        
                        // Document Title
                        doc.setFont('helvetica', 'bold')
                        doc.setFontSize(16)
                        var textWidth = doc.getTextWidth(docTitle)
                        var x = pageWidth/2 - textWidth/2
                        doc.text(docTitle, x, y)     
                        
                        // Filters
                        if (filters.length > 0) {

                            // 
                            y += 6
                            doc.setFont('helvetica', 'normal')
                            doc.setFontSize(10)
                            var text = `Filter(s):`
                            var textWidth = doc.getTextWidth(text)
                            var x = pageWidth/2 - textWidth/2
                            doc.text(text, inches_1/2+1, y)     

                            for (key in filters) {
                                y += 4
                                // 
                                doc.setFont('helvetica', 'normal')
                                var text = filters[key].start
                                var textWidth = doc.getTextWidth(text)
                                var x = inches_1/2+1
                                doc.text(text, x, y)     
                                // 
                                doc.setFont('helvetica', 'bold')
                                var text = filters[key].name
                                var textWidth2 = doc.getTextWidth(text)
                                doc.text(text, x + textWidth + 1, y)     
                                // 
                                doc.setFont('helvetica', 'normal')
                                var text = filters[key].value
                                doc.text(text, x + textWidth + textWidth2 + 1, y)     
                            }

                        }

                        // Table
                        let tblData = []
                        if (res.items.records.length > 0) {
                            for (key in res.items.records) {
                                tblData.push({
                                    "salaryTaxableFrom" : res.items.records[key].salaryTaxableFrom, 
                                    "salaryTaxableTo"   : res.items.records[key].salaryTaxableTo, 
                                    "base"              : res.items.records[key].base, 
                                    "ratePercentage"    : res.items.records[key].ratePercentage, 
                                    "rateFixed"         : res.items.records[key].rateFixed, 
                                })
                            }
                        }

                        // Define table columns and rows
                        let tableLabels = {
                            "salaryTaxableFrom" : " Taxable Salary From", 
                            "salaryTaxableTo"   : " Taxable Salary To", 
                            "base"              : " Base ", 
                            "ratePercentage"    : " Percentage Rate", 
                            "rateFixed"         : " Fixed Rate", 
                        } 
                        // table head
                        let tableData = []
                        if (Object.keys(tableLabels).length > 0) {
                            tableData[0] = []
                            for (index in tableLabels) {
                                tableData[0].push(tableLabels[index])
                            }
                        }
                        // table body
                        if (tblData.length > 0) {
                            for (index1 in tblData) {
                                if (Object.keys(tableLabels).length > 0) {
                                    tableData[parseInt(index1)+1] = []
                                    for (index2 in tableLabels) {
                                        tableData[parseInt(index1)+1].push(tblData[index1][index2])
                                    }
                                }
                            }
                        }
                        // Generate the table
                        y += 2
                        doc.autoTable({
                            body: tableData,
                            theme: "grid",
                            styles: { cellPadding: 1 },
                            headStyles: { fillColor: [200, 200, 200] }, // Light gray headers
                            startY: y,
                            didParseCell: function(data) {
                                const cellValue = data.cell.text[0]

                                // data.cell.styles.valign = 'middle'
                                data.cell.styles.fontSize = 10
                                if (tableData[0].includes(cellValue)) {
                                    data.cell.styles.halign = 'left'
                                    data.cell.styles.fillColor = [217, 217, 217]
                                    data.cell.styles.textColor = [0, 0, 0]
                                    data.cell.styles.fontSize = 9
                                } else {
                                    data.cell.styles.textColor = [0,0,0]
                                }
                        
                            },
                            didDrawPage: function (data) {

                                doc.addImage(headerImage, 'JPG', ((inches_1/2) + 40), (inches_1/5)*2, 0, headerImageHeight)

                                // // Page number at the bottom right (e.g., "Page x of y")
                                // var pageNumber = `${data.pageCount}`
                                // var y = doc.internal.pageSize.height - 10;
                                // doc.setFontSize(10);
                                // doc.text(pageNumber, (pageWidth-(inches_1/2)-doc.getTextWidth(pageNumber))-1, (doc.internal.pageSize.height - 10));
                            },
                            margin: { top: (inches_1/2) + headerImageHeight + 4 }, 
                            pageBreak: 'auto',
                            showHead: 'firstPage',
                        })
                        const tableHeight = doc.autoTable.previous.finalY - y

                        y += 6 + tableHeight
                        doc.setFont('helvetica', 'italic')
                        doc.setFontSize(10)
                        var text = `** End of Table **`
                        var textWidth = doc.getTextWidth(text)
                        var x = pageWidth/2 - textWidth/2
                        doc.text(text, x, y)    

                        // preview
                        document.getElementById('main-iframe').setAttribute('src', doc.output('bloburl'))
                        // download if mobile app
                        if (/Mobi|Android/i.test(navigator.userAgent)) { 
                            const blob = doc.output('blob')
                            const url = URL.createObjectURL(blob)
                            window.open(url, '_blank') 
                        }

                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        (function() {
            generatePDF()
        })()

    </script>
@endsection