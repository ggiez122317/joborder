@extends('layouts.print') 

@section('title', $title) 

@section('content')

    <table id="page1Header">
        <tr><td colspan="100" rowspan="1">CS Form No. 212</td></tr>
        <tr><td colspan="100" rowspan="1">Revised 2017</td></tr>
        <tr><td colspan="100" rowspan="1">PERSONAL DATA SHEET</td></tr>
        <tr><td colspan="100" rowspan="1">WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.</td></tr>
        <tr><td colspan="100" rowspan="1">&nbsp;</td></tr>
        <tr><td colspan="100" rowspan="1">READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.</td></tr>
        <tr>
            <td colspan="60" rowspan="1">Print legibly. Tick appropriate boxes ( ) and use separate sheet if necessary. Indicate N/A if not applicable.</td>
            <td colspan="15" rowspan="1">DO NOT ABBREVIATE.</td>
            <td colspan="25" rowspan="1"></td>
        </tr>
    </table>
    <table id="page1">
        <tr><td colspan="100">I. PERSONAL BACKGROUND</td></tr>

            <tr>
                <td colspan="20" rowspan="3">SURNAME<br><br>FIRST NAME<br><br>MIDDLE NAME</td>
                <td colspan="80" rowspan="1" class="lname"></td>
            </tr>
            <tr>
                <td colspan="56" rowspan="1" class="fname"></td> 
                <td colspan="24" rowspan="1">NAME EXTENSION (JR, SR)</td> 
            </tr>
            <tr>
                <td colspan="80" rowspan="1" class="mname"></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="1">DATE OF BIRTH<br>(mm/dd/yyyy)<br></td>
                <td colspan="23" rowspan="1" class="birthDate"></td>
                <td colspan="22" rowspan="3">CITIZENSHIP<br><br><br>If holder of dual cetizenship<br><br>please indicate the details</td>
                <td colspan="35" rowspan="2" class="citizenship"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">PLACE OF BIRTH</td>
                <td colspan="23" rowspan="1" class="birthPlace"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">SEX</td>
                <td colspan="23" rowspan="1" class="gender"></td>
                <td colspan="35" rowspan="1" ></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="2">CIVIL STATUS</td>
                <td colspan="23" rowspan="2" class="civilStatus"></td>
                <td colspan="15" rowspan="4">RESIDENTIAL<br>ADDRESS<br><br><br><br><br><br>ZIP CODE</td>
                <td colspan="42" rowspan="1" ></td>
            </tr>
            <tr>
                <td colspan="42" rowspan="1" ></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">HEIGHT</td>
                <td colspan="23" rowspan="1" ></td>
                <td colspan="42" rowspan="1" ></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">WEIGHT</td>
                <td colspan="23" rowspan="1" ></td>
                <td colspan="42" rowspan="1" ></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="1">BLOOD TYPE</td>
                <td colspan="23" rowspan="1" class="bloodType"></td>
                <td colspan="15" rowspan="4">PERMANENT<br>ADDRESS<br><br><br><br><br><br>ZIP CODE</td>
                <td colspan="42" rowspan="1" class="permStreet"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">GSIS ID NO</td>
                <td colspan="23" rowspan="1" class="gsis"></td>
                <td colspan="42" rowspan="1" class="permBarangay"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">PAG-IBIG ID NO</td>
                <td colspan="23" rowspan="1" class="pagibig"></td>
                <td colspan="42" rowspan="1" class="permCity"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">PHILHEALTH ID NO</td>
                <td colspan="23" rowspan="1" class="philhealth"></td>
                <td colspan="42" rowspan="1" class="permProvince"></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="1">SSS ID NO</td>
                <td colspan="23" rowspan="1" class="sss"></td>
                <td colspan="20" rowspan="1">TELEPHONE NO</td>
                <td colspan="37" rowspan="1"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">TIN ID NO</td>
                <td colspan="23" rowspan="1" class="tin"></td>
                <td colspan="20" rowspan="1">MOBILE NO</td>
                <td colspan="37" rowspan="1" class="phone"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">AGENCY EMPLOYEE NO</td>
                <td colspan="23" rowspan="1"></td>
                <td colspan="20" rowspan="1">EMAIL ADDRESS (If any)</td>
                <td colspan="37" rowspan="1" class="email"></td>
            </tr>

        <tr><td colspan="100">II. FAMILY BACKGROUND</td></tr>

            <tr>
                <td colspan="20" rowspan="3">SPOUSE'S SURNAME<br><br>FIRST NAME<br><br>MIDDLE NAME</td>
                <td colspan="38" rowspan="1" class="spouseLname"></td>
                <td colspan="27" rowspan="1">NAME of CHILDREN (Write full name and list all)</td>
                <td colspan="15" rowspan="1">DATE OF BIRTH (mm/dd/yyyy)</td>
            </tr>
            <tr>
                <td colspan="23" rowspan="1" class="spouseFname"></td>
                <td colspan="15" rowspan="1" >NAME EXTENSION (JR, SR)</td>
                <td colspan="27" rowspan="1" class="child1Name"></td>
                <td colspan="16" rowspan="1" class="child1BirthDate"></td>
            </tr>
            <tr>
                <td colspan="38" rowspan="1" class="spouseMname"></td>
                <td colspan="27" rowspan="1" class="child2Name"></td>
                <td colspan="16" rowspan="1" class="child2BirthDate"></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="1">OCCUPATION</td>
                <td colspan="38" rowspan="1" class="spouseOccupation"></td>
                <td colspan="27" rowspan="1" class="child3Name"></td>
                <td colspan="16" rowspan="1" class="child3BirthDate"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">EMPLOYER/ BUSINESS NAME</td>
                <td colspan="38" rowspan="1" class="spouseBizName"></td>
                <td colspan="27" rowspan="1" class="child4Name"></td>
                <td colspan="16" rowspan="1" class="child4BirthDate"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">BUSINESS ADDRESS</td>
                <td colspan="38" rowspan="1" class="spouseBizAddress"></td>
                <td colspan="27" rowspan="1" class="child5Name"></td>
                <td colspan="16" rowspan="1" class="child5BirthDate"></td>
            </tr>
            <tr>
                <td colspan="20" rowspan="1">TELEPHONE NO</td>
                <td colspan="38" rowspan="1" class="spouseTelNo"></td>
                <td colspan="27" rowspan="1" class="child6Name"></td>
                <td colspan="16" rowspan="1" class="child6BirthDate"></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="3">FATHER'S SURNAME<br><br>FIRST NAME<br><br>MIDDLE NAME</td>
                <td colspan="38" rowspan="1" class="fatherLname"></td>
                <td colspan="27" rowspan="1" class="child7Name"></td>
                <td colspan="16" rowspan="1" class="child7BirthDate"></td>
            </tr>
            <tr>
                <td colspan="23" rowspan="1" class="fatherFname">sss</td>
                <td colspan="15" rowspan="1">NAME EXTENSION (JR, SR)</td>
                <td colspan="27" rowspan="1" class="child8Name"></td>
                <td colspan="16" rowspan="1" class="child8BirthDate"></td>
            </tr>
            <tr>
                <td colspan="38" rowspan="1" class="fatherMname"></td>
                <td colspan="27" rowspan="1" class="child9Name"></td>
                <td colspan="16" rowspan="1" class="child9BirthDate"></td>
            </tr>

            <tr>
                <td colspan="20" rowspan="4">MOTHER'S MAIDEN NAME<br><br>SURNAME<br><br>FIRST NAME<br><br>MIDDLE NAME</td>
                <td colspan="38" rowspan="1"></td>
                <td colspan="27" rowspan="1" class="child10Name"></td>
                <td colspan="16" rowspan="1" class="child10BirthDate"></td>
            </tr>
            <tr>
                <td colspan="38" rowspan="1" class="motherLname"></td>
                <td colspan="27" rowspan="1" class="child11Name"></td>
                <td colspan="16" rowspan="1" class="child11BirthDate"></td>
            </tr>
            <tr>
                <td colspan="38" rowspan="1" class="motherFname"></td>
                <td colspan="27" rowspan="1" class="child12Name"></td>
                <td colspan="16" rowspan="1" class="child12BirthDate"></td>
            </tr>
            <tr>
                <td colspan="38" rowspan="1" class="motherMname"></td>
                <td colspan="42" rowspan="1"></td>
            </tr>

        <tr><td colspan="100">III. EDUCATIONAL BACKGROUND</td></tr>

            <tr>
                <td colspan="20" rowspan="2">LEVEL</td>
                <td colspan="23" rowspan="2">NAME OF SCHOOL<br>(Write in full)</td>
                <td colspan="22" rowspan="2">BASIC EDUCATION/DEGREE/COURSE<br>(Write in full)</td>
                <td colspan="10" rowspan="1">PERIOD OF<br>ATTENDANCE</td>
                <td colspan="11" rowspan="2">HIGHEST LEVEL/<br>UNITS EARNED<br>(If not graduated)</td>
                <td colspan="7" rowspan="2">YEAR<br>GRADUATED</td>
                <td colspan="7" rowspan="2">SCHOLARSHIP<br>/ ACADEMIC<br>HONORS<br>RECEIVED</td>
            </tr>

            <tr>
                <td colspan="5" rowspan="1">FROM</td>
                <td colspan="5" rowspan="1">TO</td>
            </tr>

    </table>

    <table id="page21">
        <tr><td colspan="100">IV. CIVIL SERVICE ELIGIBILITY</td></tr>
        <tr>
            <td colspan="2" rowspan="2">27</td>
            <td colspan="30" rowspan="2">CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER<br>SPECIAL LAWS/ CES/ CSEE BARANGAY ELIGIBILITY /<br>DRIVER'S LICENSE</td>
            <td colspan="10" rowspan="2">RATING<br>(If Applicable)</td>
            <td colspan="10" rowspan="2">DATE OF<br>EXAMINATION / <br>CONFERMENT</td>
            <td colspan="30" rowspan="2">PLACE OF EXAMINATION / CONFERMENT</td>
            <td colspan="18" rowspan="1">LICENSE (if applicable)</td>
        </tr>
        <tr>
            <td colspan="9" rowspan="1">NUMBER</td>
            <td colspan="9" rowspan="1">Date of Validity</td>
        </tr>
    </table>
    <table id="page22">
        <tr><td colspan="100">V. WORK EXPERIENCE</td></tr>
        <tr><td colspan="100">(Include private employment. Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.</td></tr>
        <tr>
            <td colspan="2" rowspan="1">28</td>
            <td colspan="14" rowspan="1">INCLUSIVE DATES<br>(mm/dd/yyyy)</td>
            <td colspan="27" rowspan="2">POSITION TITLE<br>(Write in full/Do not abbreviate)</td>
            <td colspan="29" rowspan="2">DEPARTMENT / AGENCY / OFFICE / COMPANY<br>(Write in full/Do not abbreviate)</td>
            <td colspan="6" rowspan="2">MONTHLY<br>SALARY</td>
            <td colspan="7" rowspan="2">SALARY/ JOB/<br>PAY GRADE (if<br>applicable)&<br>STEP (Format<br>"00-0")/<br>INCREMENT</td>
            <td colspan="8" rowspan="2">STATUS OF<br>APPOINTMENT</td>
            <td colspan="7" rowspan="2">GOV'T<br>SERVICE<br>(Y/ N)</td>
        </tr>
        <tr>
            <td colspan="8" rowspan="1">From</td>
            <td colspan="8" rowspan="1">To</td>
        </tr>
    </table>
    <table id="page23">
        <tr>
            <td colspan="20" rowspan="1">SIGNATURE</td>
            <td colspan="45" rowspan="1"></td>
            <td colspan="10" rowspan="1">DATE</td>
            <td colspan="25" rowspan="1"></td>
        </tr>
    </table>

    <table id="page31">
        <tr><td colspan="100">VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S</td></tr>
        <tr>
            <td colspan="2" rowspan="2">29</td>
            <td colspan="32" rowspan="2">NAME & ADDRESS OF ORGANIZATION<br>(Write in full)</td>
            <td colspan="20" rowspan="1">INCLUSIVE DATES<br>(mm/dd/yyyy)</td>
            <td colspan="10" rowspan="2">NUMBER OF<br>HOURS</td>
            <td colspan="36" rowspan="2">POSITION / NATURE OF WORK</td>
        </tr>
        <tr>
            <td colspan="10" rowspan="1">From</td>
            <td colspan="10" rowspan="1">To</td>
        </tr>

        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="34" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="10" rowspan="1"></td>
            <td colspan="36" rowspan="1"></td>
        </tr>

        <tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>
    </table>
    <table id="page32">
        <tr><td colspan="100">VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED</td></tr>
        <tr><td colspan="100">(Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)</td></tr>
        <tr>
            <td colspan="2" rowspan="2">30</td>
            <td colspan="37" rowspan="2">TITLE OF LEARNING AND DEVELOPMENT<br>INTERVENTIONS/TRAINING PROGRAMS<br>(Write in full)</td>
            <td colspan="24" rowspan="1">INCLUSIVE DATES OF ATTENDANCE<br>(mm/dd/yyyy)</td>
            <td colspan="12" rowspan="2">NUMBER OF HOURS</td>
            <td colspan="13" rowspan="2">Type of LD ( Managerial/<br>Supervisory/ Technical/etc)</td>
            <td colspan="12" rowspan="2">CONDUCTED/<br>SPONSORED BY<br>(Write in full)</td>
        </tr>
        <tr>
            <td colspan="12" rowspan="1">From</td>
            <td colspan="12" rowspan="1">To</td>
        </tr>
    </table>
    <table id="page33">
        <tr><td colspan="100">VIII. OTHER INFORMATION</td></tr>
        <tr>
            <td colspan="2" rowspan="1">31</td>
            <td colspan="31" rowspan="1">SPECIAL SKILLS and HOBBIES</td>
            <td colspan="2" rowspan="1">32</td>
            <td colspan="31" rowspan="1">NON-ACADEMIC DISTINCTIONS / RECOGNITION<br>(Write in full)</td>
            <td colspan="2" rowspan="1">33</td>
            <td colspan="32" rowspan="1">MEMBERSHIP IN ASSOCIATION/ORGANIZATION<br>(Write in full)</td>
        </tr>

        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="33" rowspan="1"></td>
            <td colspan="33" rowspan="1"></td>
            <td colspan="34" rowspan="1"></td>
        </tr>

        <tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>
    </table>
    <table id="page34">
        <tr>
            <td colspan="20" rowspan="1">SIGNATURE</td>
            <td colspan="46" rowspan="1"></td>
            <td colspan="10" rowspan="1">DATE</td>
            <td colspan="24" rowspan="1"></td>
        </tr>
    </table>

    <table id="page41">

        <tr>
            <td colspan="56" rowspan="1">
                Are you related by consanguinity or affinity to the appointing or recommending authority,
                or to the chief of bureau or office or to the person who has immediate supervision over
                you in the Office, Bureau or Department where you will be apppointed,
                <br>
                a. within the third degree?
                <br>
                b. within the fourth degree (for Local Government Unit - Career Employees)?
                <br>
                <br>
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                a. Have you ever been found guilty of any administrative offense?
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                b. Have you been criminally charged before any court?
                <br>
                <br>
                <br>
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                Have you ever been convicted of any crime or violation of any law, decree, ordinance or
                regulation by any court or tribunal?
                <br>
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                Have you ever been separated from the service in any of the following modes:
                resignation, retirement, dropped from the rolls, dismissal, termination, end of term,
                finished contract or phased out (abolition) in the public or private sector?
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                a. Have you ever been a candidate in a national or local election held within the last year
                (except Barangay election)?
                <br>
                <br>
                <br>
                b. Have you resigned from the government service during the three (3)-month period
                before the last election to promote/actively campaign for a national or local candidate?
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                Have you acquired the status of an immigrant or permanent resident of another country?
                <br>
                <br>
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>
        <tr>
            <td colspan="56" rowspan="1">
                Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled
                Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer
                the following items:
                <br>
                a. Are you a member of any indigenous group?
                <br>
                <br>
                <br>
                b. Are you a person with disability?
                <br>
                <br>
                <br>
                c. Are you a solo parent?
                <br>
                <br>
            </td>
            <td colspan="34" rowspan="1"></td>
        </tr>

    </table>
    <table id="page42">
        <tr>
            <td colspan="4">41</td>
            <td colspan="96">REFERENCES (Person not related by consanguinity or affinity to applicant /appointee)</td>
        </tr>
        <tr>
            <td colspan="55">NAME</td>
            <td colspan="27">ADDRESS</td>
            <td colspan="18">TEL. NO.</td>
        </tr>
        <tr>
            <td colspan="55"></td>
            <td colspan="27"></td>
            <td colspan="18"></td>
        </tr>
        <tr>
            <td colspan="55"></td>
            <td colspan="27"></td>
            <td colspan="18"></td>
        </tr>
        <tr>
            <td colspan="55"></td>
            <td colspan="27"></td>
            <td colspan="18"></td>
        </tr>
        <tr>
            <td colspan="4">42</td>
            <td colspan="96">
                I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct and
                complete statement pursuant to the provisions of pertinent laws, rules and regulations of the Republic of the
                Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated
                herein. I agree that any misrepresentation made in this document and its attachments shall cause the filing of
                administrative/criminal case/s against me.
            </td>
        </tr>
    </table>
    <table id="page43">
        <tr><td colspan="100"> Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.) PLEASE INDICATE ID Number and Date of Issuance</td></tr>
        <tr><td colspan="100">Government Issued ID:</td></tr>
        <tr><td colspan="100">ID/License/Passport No.:</td></tr>
        <tr><td colspan="100">Date/Place of Issuance:</td></tr>
    </table>
    <table id="page44">
        <tr><td colspan="100"><br><br></td></tr>
        <tr><td colspan="100">Signature (Sign inside the box)</td></tr>
        <tr><td colspan="100">&nbsp;</td></tr>
        <tr><td colspan="100">Date Accomplished</td></tr>
    </table>
    <table id="page45">
        <tr><td>ID picture taken within the last 6 months 3.5 cm. X 4.5 cm (passport size)<br><br>With full and handwritten name tag and signature over printed name<br><br>Computer generated or photocopied picture is not acceptable</td></tr>
    </table>
    <table id="page46">
        <tr><td><br><br><br><br></td></tr>
        <tr><td>Right Thumbmark</td></tr>
    </table>
    <table id="page47">
        <tr><td><br><br><br></td></tr>
        <tr><td>Person Administering Oath</td></tr>
    </table>

@endsection

@section('scripts')
    <script>
        
        let docTitle = "PDS"

        const { jsPDF } = window.jspdf
        const inches_1 = 25.4
        let pageWidth = 0
        let y = inches_1/2

        // generator
        function generatePDF()
        {

            // document details
            var doc = new jsPDF({
                orientation: 'p',
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
            pageWidth = doc.internal.pageSize.getWidth()

            getItems(doc, pageWidth)
            return
            
        } 

        // page 1
        function generatePage1Data(data)
        {

            tableID = 'page1'

            // personal information
            document.querySelectorAll(`#${tableID} .lname`).forEach(el => el.textContent = data.personal_information.lname)
            document.querySelectorAll(`#${tableID} .fname`).forEach(el => el.textContent = data.personal_information.fname)
            document.querySelectorAll(`#${tableID} .mname`).forEach(el => el.textContent = data.personal_information.mname)
            document.querySelectorAll(`#${tableID} .birthDate`).forEach(el => el.textContent = data.personal_information.birthDate)
            document.querySelectorAll(`#${tableID} .birthPlace`).forEach(el => el.textContent = data.personal_information.birthPlace)
            document.querySelectorAll(`#${tableID} .gender`).forEach(el => el.textContent = data.personal_information.gender)
            document.querySelectorAll(`#${tableID} .bloodType`).forEach(el => el.textContent = data.personal_information.bloodType)
            document.querySelectorAll(`#${tableID} .gsis`).forEach(el => el.textContent = data.personal_information.gsis)
            document.querySelectorAll(`#${tableID} .pagibig`).forEach(el => el.textContent = data.personal_information.pagibig)
            document.querySelectorAll(`#${tableID} .philhealth`).forEach(el => el.textContent = data.personal_information.philhealth)
            document.querySelectorAll(`#${tableID} .sss`).forEach(el => el.textContent = data.personal_information.sss)
            document.querySelectorAll(`#${tableID} .tin`).forEach(el => el.textContent = data.personal_information.tin)
            document.querySelectorAll(`#${tableID} .citizenship`).forEach(el => el.textContent = data.personal_information.citizenship)
            document.querySelectorAll(`#${tableID} .permStreet`).forEach(el => el.textContent = data.personal_information.permStreet)
            document.querySelectorAll(`#${tableID} .permBarangay`).forEach(el => el.textContent = data.personal_information.permBarangay)
            document.querySelectorAll(`#${tableID} .permCity`).forEach(el => el.textContent = data.personal_information.permCity)
            document.querySelectorAll(`#${tableID} .permProvince`).forEach(el => el.textContent = data.personal_information.permProvince)
            document.querySelectorAll(`#${tableID} .phone`).forEach(el => el.textContent = data.personal_information.phone)
            document.querySelectorAll(`#${tableID} .email`).forEach(el => el.textContent = data.personal_information.email)

            // family background
            document.querySelectorAll(`#${tableID} .spouseLname`).forEach(el => el.textContent = data.family_background.spouseLname)
            document.querySelectorAll(`#${tableID} .spouseFname`).forEach(el => el.textContent = data.family_background.spouseFname)
            document.querySelectorAll(`#${tableID} .spouseMname`).forEach(el => el.textContent = data.family_background.spouseMname)
            document.querySelectorAll(`#${tableID} .spouseOccupation`).forEach(el => el.textContent = data.family_background.spouseOccupation)
            document.querySelectorAll(`#${tableID} .spouseBizName`).forEach(el => el.textContent = data.family_background.spouseBizName)
            document.querySelectorAll(`#${tableID} .spouseBizAddress`).forEach(el => el.textContent = data.family_background.spouseBizAddress)
            document.querySelectorAll(`#${tableID} .spouseTelNo`).forEach(el => el.textContent = data.family_background.spouseTelNo)
            document.querySelectorAll(`#${tableID} .fatherLname`).forEach(el => el.textContent = data.family_background.fatherLname)
            document.querySelectorAll(`#${tableID} .fatherFname`).forEach(el => el.textContent = data.family_background.fatherFname)
            document.querySelectorAll(`#${tableID} .fatherMname`).forEach(el => el.textContent = data.family_background.fatherMname)
            document.querySelectorAll(`#${tableID} .motherLname`).forEach(el => el.textContent = data.family_background.motherLname)
            document.querySelectorAll(`#${tableID} .motherFname`).forEach(el => el.textContent = data.family_background.motherFname)
            document.querySelectorAll(`#${tableID} .motherMname`).forEach(el => el.textContent = data.family_background.motherMname)

            document.querySelectorAll(`#${tableID} .child1Name`).forEach(el => el.textContent = data.family_background.child1Name)
            document.querySelectorAll(`#${tableID} .child1BirthDate`).forEach(el => el.textContent = data.family_background.child1BirthDate)
            document.querySelectorAll(`#${tableID} .child2Name`).forEach(el => el.textContent = data.family_background.child2Name)
            document.querySelectorAll(`#${tableID} .child2BirthDate`).forEach(el => el.textContent = data.family_background.child2BirthDate)
            document.querySelectorAll(`#${tableID} .child3Name`).forEach(el => el.textContent = data.family_background.child3Name)
            document.querySelectorAll(`#${tableID} .child3BirthDate`).forEach(el => el.textContent = data.family_background.child3BirthDate)
            document.querySelectorAll(`#${tableID} .child4Name`).forEach(el => el.textContent = data.family_background.child4Name)
            document.querySelectorAll(`#${tableID} .child4BirthDate`).forEach(el => el.textContent = data.family_background.child4BirthDate)
            document.querySelectorAll(`#${tableID} .child5Name`).forEach(el => el.textContent = data.family_background.child5Name)
            document.querySelectorAll(`#${tableID} .child5BirthDate`).forEach(el => el.textContent = data.family_background.child5BirthDate)
            document.querySelectorAll(`#${tableID} .child6Name`).forEach(el => el.textContent = data.family_background.child6Name)
            document.querySelectorAll(`#${tableID} .child6BirthDate`).forEach(el => el.textContent = data.family_background.child6BirthDate)
            document.querySelectorAll(`#${tableID} .child7Name`).forEach(el => el.textContent = data.family_background.child7Name)
            document.querySelectorAll(`#${tableID} .child7BirthDate`).forEach(el => el.textContent = data.family_background.child7BirthDate)
            document.querySelectorAll(`#${tableID} .child8Name`).forEach(el => el.textContent = data.family_background.child8Name)
            document.querySelectorAll(`#${tableID} .child8BirthDate`).forEach(el => el.textContent = data.family_background.child8BirthDate)
            document.querySelectorAll(`#${tableID} .child9Name`).forEach(el => el.textContent = data.family_background.child9Name)
            document.querySelectorAll(`#${tableID} .child9BirthDate`).forEach(el => el.textContent = data.family_background.child9BirthDate)
            document.querySelectorAll(`#${tableID} .child10Name`).forEach(el => el.textContent = data.family_background.child10Name)
            document.querySelectorAll(`#${tableID} .child10BirthDate`).forEach(el => el.textContent = data.family_background.child10BirthDate)
            document.querySelectorAll(`#${tableID} .child11Name`).forEach(el => el.textContent = data.family_background.child11Name)
            document.querySelectorAll(`#${tableID} .child11BirthDate`).forEach(el => el.textContent = data.family_background.child11BirthDate)
            document.querySelectorAll(`#${tableID} .child12Name`).forEach(el => el.textContent = data.family_background.child12Name)
            document.querySelectorAll(`#${tableID} .child12BirthDate`).forEach(el => el.textContent = data.family_background.child12BirthDate)

            // educational background
            if (data.educational_background.length > 0) {
                for (key in data.educational_background) {
                    $(`#${tableID} tbody`).append(`
                        <tr>
                            <td colspan="20" rowspan="1">${data.educational_background[key].level}</td>
                            <td colspan="23" rowspan="1">${data.educational_background[key].schoolName}</td>
                            <td colspan="22" rowspan="1">${data.educational_background[key].degree}</td>
                            <td colspan="5" rowspan="1">${data.educational_background[key].dateAttendedFrom}</td>
                            <td colspan="5" rowspan="1">${data.educational_background[key].dateAttendedTo}</td>
                            <td colspan="11" rowspan="1">${data.educational_background[key].highestLevelEarned}</td>
                            <td colspan="7" rowspan="1">${data.educational_background[key].yearGraduated}</td>
                            <td colspan="7" rowspan="1">${data.educational_background[key].scholarship}</td>
                        </tr>
                    `)
                }
            }

            $(`#${tableID} tbody`).append(`
                <tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>
                <tr>
                    <td colspan="20" rowspan="1">SIGNATURE</td>
                    <td colspan="45" rowspan="1"></td>
                    <td colspan="10" rowspan="1">DATE</td>
                    <td colspan="25" rowspan="1"></td>
                </tr>
            `)

        } 
        function generatePage2Data(data)
        {

            tableID = 'page21'
            if (data.civil_service_eligibilities.length > 0) {
                for (key in data.civil_service_eligibilities) {
                    $(`#${tableID} tbody`).append(`
                        <tr>
                            <td colspan="32" rowspan="1">${data.civil_service_eligibilities[key].name}</td>
                            <td colspan="10" rowspan="1">${data.civil_service_eligibilities[key].rating}</td>
                            <td colspan="10" rowspan="1">${data.civil_service_eligibilities[key].dateExamination}</td>
                            <td colspan="30" rowspan="1">${data.civil_service_eligibilities[key].placeExamination}</td>
                            <td colspan="9" rowspan="1">${data.civil_service_eligibilities[key].licenseNumber}</td>
                            <td colspan="9" rowspan="1">${data.civil_service_eligibilities[key].licenseDateValidity}</td>
                        </tr>
                    `)
                }
            }
            $(`#${tableID} tbody`).append(`<tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>`)

            tableID = 'page22'
            if (data.work_experiences.length > 0) {
                for (key in data.work_experiences) {
                    $(`#${tableID} tbody`).append(`
                        <tr>
                            <td colspan="8" rowspan="1">${data.work_experiences[key].dateFrom}</td>
                            <td colspan="8" rowspan="1">${data.work_experiences[key].dateTo}</td>
                            <td colspan="27" rowspan="1">${data.work_experiences[key].position}</td>
                            <td colspan="29" rowspan="1">${data.work_experiences[key].company}</td>
                            <td colspan="6" rowspan="1">${data.work_experiences[key].salary}</td>
                            <td colspan="7" rowspan="1">${data.work_experiences[key].salaryGrade}</td>
                            <td colspan="8" rowspan="1">${data.work_experiences[key].appointmentStatus}</td>
                            <td colspan="7" rowspan="1">${data.work_experiences[key].isGovt}</td>
                        </tr>
                    `)
                }
            }
            $(`#${tableID} tbody`).append(`<tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>`)

        } 
        function generatePage3Data(data)
        {

            tableID = 'page32'
            if (data.training_programs.length > 0) {
                for (key in data.training_programs) {
                    $(`#${tableID} tbody`).append(`
                        <tr>
                            <td colspan="39" rowspan="1">${data.training_programs[key].trainingName}</td>
                            <td colspan="12" rowspan="1">${data.training_programs[key].dateFrom}</td>
                            <td colspan="12" rowspan="1">${data.training_programs[key].dateTo}</td>
                            <td colspan="12" rowspan="1">${data.training_programs[key].hours}</td>
                            <td colspan="13" rowspan="1">${data.training_programs[key].ldType}</td>
                            <td colspan="12" rowspan="1">${data.training_programs[key].sponsor}</td>
                        </tr>
                    `)
                }
            }
            $(`#${tableID} tbody`).append(`<tr><td colspan="100" rowspan="1">(Continue on separate sheet if necessary)</td></tr>`)

        } 

        function generatePage1Numbers(doc)
        {
            let text = ''
            doc.setFontSize(6)
            doc.setTextColor(0, 0, 0)
            doc.setFont('helvetica', 'normal')

            x = 11.8
            y = 59

            // personal
            doc.text('2.', x, y)
            y += 17.2
            doc.text('3.', x, y)
            doc.text('16.', x+83.2, y)
            y += 9.8
            doc.text('4.', x, y)
            y += 5.9
            doc.text('5.', x, y)
            y += 5.77
            doc.text('6.', x, y)
            doc.text('17.', x+83.2, y)
            y += 11.4
            doc.text('7.', x, y)
            y += 5.74
            doc.text('8.', x, y)
            y += 5.74
            doc.text('9.', x, y)
            doc.text('18.', x+83.2, y)
            y += 5.74
            doc.text('10.', x-1, y)
            y += 5.74
            doc.text('11.', x-1, y)
            y += 5.74
            doc.text('12.', x-1, y)
            y += 5.74
            doc.text('13.', x-1, y)
            doc.text('19.', x+83.2, y)
            y += 5.74
            doc.text('14.', x-1, y)
            doc.text('20.', x+83.2, y)
            y += 5.74
            doc.text('15.', x-1, y)
            doc.text('21.', x+83.2, y)

            // family
            y += 12.2
            doc.text('22.', x-1, y)
            doc.text('23.', x+112.6, y)
            y += 40.15
            doc.text('24.', x-1, y)
            y += 17.55
            doc.text('25.', x-1, y)
            
            // educational
            y += 29
            doc.text('26.', x-1, y)

        } 
        function generatePage4Numbers(doc)
        {
            let text = ''
            doc.setFontSize(8)
            doc.setTextColor(0, 0, 0)
            doc.setFont('helvetica', 'normal')

            x = 14

            y = 13.5
            doc.text('34.', x, y)

            y += 31.5
            doc.text('35.', x, y)

            y += 40.8
            doc.text('36.', x, y)

            y += 18.2
            doc.text('37.', x, y)

            y += 18.2
            doc.text('38.', x, y)

            y += 27.8
            doc.text('39.', x, y)

            y += 18.2
            doc.text('40.', x, y)

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

        // items
        function getItems(doc, pageWidth)
        {

            apiCall(`/api/{{ "$controller" }}/print-pds-data/0/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {
    
                        numColumns = 100
                        columnWidth = (pageWidth - ((inches_1/5)*4)) / numColumns

                        columnStyles = {}
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles[i] = { cellWidth: columnWidth };
                        }

                        /** ****************** PAGE 1 ****************** */
                        
                        // header
                        y = (inches_1/5)*2
                        doc.autoTable({
                            html: '#page1Header', 
                            theme: "plain", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
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

                                data.cell.styles.fontSize       = 10
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = { left: 0.8, right: 0.8, top: 0, bottom: 0 }
                                
                                if (data.row.index === 0) {
                                    data.cell.styles.fontStyle = 'bold'
                                    data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 1, bottom: 0 }
                                }
                                if (data.row.index === 1) {
                                    data.cell.styles.fontSize = 8
                                }
                                if (data.row.index === 2) { 
                                    data.cell.styles.fontSize = 26
                                    data.cell.styles.fontStyle = 'bold'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 1.8, bottom: 1.8 }
                                }
                                if (data.row.index === 3) data.cell.styles.fontSize = 7
                                if (data.row.index === 5) {
                                    data.cell.styles.fontSize = 7
                                    data.cell.styles.fontStyle = 'bold'
                                }
                                if (data.row.index === 6) {
                                    data.cell.styles.fontSize = 7
                                    data.cell.styles.fontStyle = 'normal'
                                    if (data.column.index === 60) data.cell.styles.fontStyle = 'bold'
                                    data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 0.4, bottom: 1 }
                                }
                            }, 
                            didDrawCell: function (data) {
                                if (data.row.index === 6) { 
                                    if (data.column.index === 75) {
                                        let doc = data.doc;
                                        let x = data.cell.x;
                                        let y = data.cell.y;
                                        let width = data.cell.width;
                                        let height = data.cell.height;

                                        let col1Width = width * 0.4;
                                        let col2Width = width * 0.6;

                                        var text = `1. CS ID No.`
                                        var textWidth = doc.getTextWidth(text)
                                        
                                        // Draw outer rectangle (small box)
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.setFillColor(153, 153, 153)
                                        doc.rect(x, y-0.2, (width - 0.5)-(width-textWidth)+5, height-0.5, 'F'); 

                                        // Draw outer rectangle (main box)
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y-0.2, (width - 0.5), height-0.5); 

                                        doc.setTextColor(255, 255, 255);
                                        doc.setFontSize(8);
                                        doc.setFont('helvetica', 'bold');
                                        doc.text(text, x + 1, (y + height / 2) - 0.7, { align: "left", baseline: "middle" });

                                    }
                                }
                            }
                        })

                        // table
                        generatePage1Data(res.items)
                        y += (doc.autoTable.previous.finalY - y) 
                        doc.autoTable({
                            html: '#page1', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if (data.row.index <= 33) {
                                    if (data.column.index === 0) {
                                        data.cell.styles.valign = 'top'
                                        data.cell.styles.cellPadding = { left: 4, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                    } else {
                                        data.cell.styles.fontSize = 8
                                        // name extension
                                        if (data.row.index === 2) {
                                            if (data.column.index === 76) {
                                                data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top-0.8, bottom: defaultPadding.bottom }
                                                data.cell.styles.fontSize = 4.8
                                                data.cell.styles.valign = 'top'
                                            }
                                        }
                                        if (data.column.index === 43) {
                                            data.cell.styles.valign = 'top'
                                            data.cell.styles.cellPadding = { left: 4, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                            data.cell.styles.fontSize = 6
                                            if ([4,7,11].includes(data.row.index)) {
                                                data.cell.styles.valign = 'top'
                                            }
                                            if ([20, 27].includes(data.row.index)) {
                                                data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: defaultPadding.top-0.8, bottom: defaultPadding.bottom }
                                                data.cell.styles.fontSize = 4.8
                                                data.cell.styles.valign = 'top'
                                            }
                                        }
                                        if (data.row.index === 19) {
                                            if (data.column.index === 58) {
                                                data.cell.styles.valign = 'top'
                                                data.cell.styles.cellPadding = { left: 4, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                                data.cell.styles.fontSize = 6
                                            }
                                            if (data.column.index === 85) {
                                                data.cell.styles.valign = 'top'
                                                data.cell.styles.fontSize = 5.7
                                            }
                                        }
                                    }
                                } else {
                                    data.cell.styles.fontSize = 8
                                    if (data.row.index === 34) {
                                        data.cell.styles.fontSize = 6
                                        if (data.column.index === 65) data.cell.styles.fontSize = 5
                                        if (data.column.index === 75) data.cell.styles.fontSize = 5
                                        if (data.column.index === 86) data.cell.styles.fontSize = 5
                                        if (data.column.index === 93) data.cell.styles.fontSize = 4.8
                                    }
                                    if (data.row.index === 35) {
                                        if (data.column.index >= 65) data.cell.styles.fontSize = 5
                                    }
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.cellPadding    = { left: 0.8, right: 0.8, top: 1, bottom: 1 }
                                    if ([36,37,38,39,40].includes(data.row.index)) {
                                        if (data.column.index === 0) {
                                            data.cell.styles.fontSize = 6
                                            data.cell.styles.halign = 'left'
                                        }
                                    }
                                    if (data.row.index === 41) {
                                        data.cell.styles.fontSize = 6
                                        data.cell.styles.fontStyle = 'italic' 
                                        data.cell.styles.fillColor = [240, 240, 240] 
                                        data.cell.styles.textColor = [255, 0, 0] 
                                    }
                                    if (data.row.index === 42) {
                                        data.cell.styles.fontSize = 6
                                        if (data.column.index === 0) data.cell.styles.fontStyle = 'bold'
                                        data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 2, bottom: 2 }
                                    }
                                }

                                if ([0, 18, 33].includes(data.row.index)) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 10 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                            }
                        })
                        generatePage1Numbers(doc)


                        /** ****************** PAGE 2 ****************** */
                        doc.addPage();

                        generatePage2Data(res.items)

                        y = (inches_1/5)*2
                        // generatePage21Data()
                        doc.autoTable({
                            html: '#page21', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if ([1,2].includes(data.row.index)) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    if (data.column.index == 0) data.cell.styles.valign = 'top'
                                }

                                if (data.row.index >=3) {
                                    data.cell.styles.fontSize = 8
                                    if (data.column.index === 0) {
                                        data.cell.styles.halign = 'left'
                                    }
                                }

                                // title
                                if (["IV. CIVIL SERVICE ELIGIBILITY", "V. WORK EXPERIENCE"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 10 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                // footer
                                if (["(Continue on separate sheet if necessary)"].includes(data.cell.text[0])) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [240, 240, 240] 
                                    data.cell.styles.textColor = [255, 0, 0] 
                                }

                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        // generatePage22Data()
                        doc.autoTable({
                            html: '#page22', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if ([2,3].includes(data.row.index)) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    if (data.row.index===2 && data.column.index == 0) data.cell.styles.valign = 'top'
                                    if ([78,85,93].includes(data.column.index)) data.cell.styles.fontSize = 5
                                }

                                
                                if (data.row.index >=4) {
                                    data.cell.styles.fontSize = 8
                                    if (data.column.index === 0) {
                                        data.cell.styles.halign = 'left'
                                    }
                                }

                                // title
                                if (["V. WORK EXPERIENCE"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 10 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                
                                if (["(Include private employment. Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet."].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 7 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                // footer
                                if (["(Continue on separate sheet if necessary)"].includes(data.cell.text[0])) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [240, 240, 240] 
                                    data.cell.styles.textColor = [255, 0, 0] 
                                }

                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        // generatePage23Data()
                        doc.autoTable({
                            html: '#page23', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontStyle = 'normal'
                                data.cell.styles.textColor = [0, 0, 0]
                                data.cell.styles.cellPadding = defaultPadding
                                data.cell.styles.valign = 'middle'
                                data.cell.styles.halign = 'center'
                                data.cell.styles.fontSize = 6
                                if (data.column.index === 0) data.cell.styles.fontStyle = 'bold'
                                data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 2, bottom: 2 }

                            }
                        })

                        /** ****************** PAGE 3 ****************** */
                        doc.addPage();

                        generatePage3Data(res.items)

                        y = (inches_1/5)*2
                        // generatePage31Data()
                        doc.autoTable({
                            html: '#page31', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if ([1,2].includes(data.row.index)) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    if (data.column.index == 0) data.cell.styles.valign = 'top'
                                }

                                // title
                                if (["VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 9 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                // footer
                                if (["(Continue on separate sheet if necessary)"].includes(data.cell.text[0])) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [240, 240, 240] 
                                    data.cell.styles.textColor = [255, 0, 0] 
                                }

                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        // generatePage32Data()
                        doc.autoTable({
                            html: '#page32', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'center'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if ([2,3].includes(data.row.index)) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    if (data.row.index===2 && data.column.index == 0) data.cell.styles.valign = 'top'
                                    if ([78,85,93].includes(data.column.index)) data.cell.styles.fontSize = 5
                                }

                                if (data.row.index >=4) {
                                    data.cell.styles.fontSize = 8
                                    if (data.column.index === 0) {
                                        data.cell.styles.halign = 'left'
                                    }
                                }

                                // title
                                if (["VII. LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 10 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }
                                if (["(Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 7 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                // footer
                                if (["(Continue on separate sheet if necessary)"].includes(data.cell.text[0])) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [240, 240, 240] 
                                    data.cell.styles.textColor = [255, 0, 0] 
                                }

                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        // generatePage33Data()
                        doc.autoTable({
                            html: '#page33', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontSize       = 6
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if ([1,2].includes(data.row.index)) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    if (data.row.index===1 && data.column.index == 0) data.cell.styles.valign = 'top'
                                    if ([78,85,93].includes(data.column.index)) data.cell.styles.fontSize = 5
                                }

                                // title
                                if (["VIII. OTHER INFORMATION"].includes(data.cell.text[0])) {
                                    data.cell.styles.cellPadding = defaultPadding
                                    data.cell.styles.fontSize = 10 
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [153, 153, 153] 
                                    data.cell.styles.textColor = [255, 255, 255] 
                                }

                                // footer
                                if (["(Continue on separate sheet if necessary)"].includes(data.cell.text[0])) {
                                    data.cell.styles.valign = 'middle'
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.fontStyle = 'italic' 
                                    data.cell.styles.fillColor = [240, 240, 240] 
                                    data.cell.styles.textColor = [255, 0, 0] 
                                }

                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) 
                        // generatePage34Data()
                        doc.autoTable({
                            html: '#page34', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 0.8, right: 0.8, top: 1.25, bottom: 1.25 }

                                data.cell.styles.fontStyle = 'normal'
                                data.cell.styles.textColor = [0, 0, 0]
                                data.cell.styles.cellPadding = defaultPadding
                                data.cell.styles.valign = 'middle'
                                data.cell.styles.halign = 'center'
                                data.cell.styles.fontSize = 6
                                if (data.column.index === 0) data.cell.styles.fontStyle = 'bold'
                                data.cell.styles.cellPadding = { left: 0.8, right: 0.8, top: 2, bottom: 2 }

                            }
                        })

                        /** ****************** PAGE 4 ****************** */
                        doc.addPage();

                        tableWidthMain = pageWidth - ((inches_1/5)*4)
                        tableWidthCol2 = 50
                        tableWidthCol1 = tableWidthMain - tableWidthCol2

                        tableMargin = 1

                        tableWidthCol12 = (tableWidthCol1/2) - (tableMargin*2)
                        
                        columnStyles = {}
                        columnWidth = tableWidthCol1 / numColumns
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles[i] = { cellWidth: columnWidth };
                        }

                        y = (inches_1/5)*2
                        // generatePage41Data()
                        doc.autoTable({
                            html: '#page41', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: (inches_1/5)*2, right: (inches_1/5)*2, top: 0, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 1, right: 1, top: 1, bottom: 1 }

                                data.cell.styles.fontSize       = 8
                                data.cell.styles.fontStyle      = 'normal'
                                data.cell.styles.valign         = 'top'
                                data.cell.styles.halign         = 'left'
                                data.cell.styles.textColor      = [0, 0, 0]
                                data.cell.styles.cellPadding    = defaultPadding

                                if (data.column.index === 0) {
                                    data.cell.styles.cellPadding = { left: defaultPadding.left+8, right: defaultPadding.right, top: defaultPadding.top, bottom: defaultPadding.bottom }
                                }
                            }, 
                            didDrawCell: function (data) {

                                const rectWidth     = 3
                                const rectHeight    = 3

                                let doc = data.doc;
                                let x = data.cell.x;
                                let y = data.cell.y;
                                let width = data.cell.width;
                                let height = data.cell.height;

                                let col1Width = width * 0.4;
                                let col2Width = width * 0.6;

                                doc.setTextColor(0, 0, 0);
                                doc.setFontSize(8);

                                let text = ''
                                let textWidth = 0

                                x += 4
                                y += 4

                                if (data.row.index !== 4 && data.column.index === 56) {

                                    if (data.row.index === 0) {
                                        y += 6

                                        text = `YES`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                        text = `No`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                        y += 4
                                    }

                                    if (data.row.index === 1) {
                                        doc.rect(x-4, y-4, width, height/2) 
                                    }

                                    if (data.row.index === 6) y += 7

                                    text = `YES`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                    text = `No`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                    y += 6
                                    text = `If YES, give details${data.row.index===5?' (country)':''}:`
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x, y) 
                                    
                                    // doc.line(x + textWidth + 2, y, x + (width-8), y)
                                    y += 6
                                    if (data.row.index===6) y -= 4
                                    doc.line(data.row.index===6?(x + textWidth + 2):x, y, x + (width-8), y)

                                    if (data.row.index===6) {

                                        // 
                                        y += 2

                                        text = `YES`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                        text = `No`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                        y += 6
                                        text = `If YES, give details${data.row.index===5?' (country)':''}:`
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x, y) 

                                        // doc.line(x + textWidth + 2, y, x + (width-8), y)
                                        y += 6
                                        if (data.row.index===6) y -= 4
                                        doc.line(data.row.index===6?(x + textWidth + 2):x, y, x + (width-8), y)

                                        // 
                                        y += 2

                                        text = `YES`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                        text = `No`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                        y += 6
                                        text = `If YES, give details${data.row.index===5?' (country)':''}:`
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x, y) 

                                        // doc.line(x + textWidth + 2, y, x + (width-8), y)
                                        y += 6
                                        if (data.row.index===6) y -= 4
                                        doc.line(data.row.index===6?(x + textWidth + 2):x, y, x + (width-8), y)


                                    }

                                    // 
                                    if ([1].includes(data.row.index)) {

                                        if (data.row.index === 1) y += 6

                                        text = `YES`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                        text = `No`
                                        doc.setDrawColor(0, 0, 0); 
                                        doc.setLineWidth(0.3);
                                        doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                        textWidth = doc.getTextWidth(text)
                                        doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                        y += 6
                                        text = `If YES, give details:`
                                        doc.text(text, x, y) 

                                        if (data.row.index === 1)  {

                                            y += 4
                                            text = `Date Filed:`
                                            textWidth = doc.getTextWidth(text)
                                            doc.text(text, x, y) 
                                            
                                            y += 4
                                            text = `Status of Case/s:`
                                            textWidth = doc.getTextWidth(text)>textWidth?doc.getTextWidth(text):textWidth
                                            doc.text(text, x, y) 

                                            y -= 4
                                            doc.line(x+textWidth+2, y, x + (width-8), y)

                                            y += 4
                                            doc.line(x+textWidth+2, y, x + (width-8), y)

                                        }

                                    }

                                } 
                                
                                if (data.row.index === 4 && data.column.index === 56) {

                                    doc.rect(x-4, y-4, width, height/2) 

                                    // 
                                    text = `YES`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                    text = `No`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                    y += 8
                                    text = `If YES, give details${data.row.index===5?' (country)':''}:`
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x, y) 

                                    doc.line(x + textWidth + 2, y, x + (width-8), y)

                                    y += 4
                                    // 
                                    text = `YES`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth+1.5, y+rectHeight-0.5) 

                                    text = `No`
                                    doc.setDrawColor(0, 0, 0); 
                                    doc.setLineWidth(0.3);
                                    doc.rect(x + rectWidth + textWidth + 4, y, rectWidth, rectHeight) 
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x+rectWidth + textWidth + 6 + rectWidth + 1.5, y+rectHeight-0.5) 

                                    y += 8
                                    text = `If YES, give details${data.row.index===5?' (country)':''}:`
                                    textWidth = doc.getTextWidth(text)
                                    doc.text(text, x, y) 

                                    doc.line(x + textWidth + 2, y, x + (width-8), y)

                                }
                            }
                        })
                        generatePage4Numbers(doc)

                        y += (doc.autoTable.previous.finalY - y) 
                        temp_y = y
                        temp_height = 0
                        // generatePage42Data()

                        columnStyles2 = {}
                        columnWidth2 = tableWidthCol12 / numColumns
                        for (let i = 0; i < numColumns; i++) {
                            columnStyles2[i] = { cellWidth: columnWidth2 };
                        }
                        doc.autoTable({
                            html: '#page42', 
                            theme: "grid", 
                            startY: y+1,  
                            margin: { left: ((inches_1/5)*2)+1, right: ((inches_1/5)*2)+tableWidthCol2+1, top: 0+1, bottom: 0 },
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles2, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 1, right: 1, top: 0.8, bottom: 0.8 }

                                data.cell.styles.textColor = [0, 0, 0]
                                data.cell.styles.cellPadding = defaultPadding
                                data.cell.styles.valign = 'middle'
                                data.cell.styles.halign = 'center'
                                data.cell.styles.fontSize = 8

                                if ([0,5].includes(data.row.index)) {
                                    data.cell.styles.halign = 'left'
                                    if ([0].includes(data.column.index)) {
                                        data.cell.styles.valign = 'top'
                                    }
                                }

                            }
                        })
                        temp_height += (doc.autoTable.previous.finalY - y + 1)
                        doc.rect(((inches_1/5)*2), y, tableWidthCol1, (doc.autoTable.previous.finalY - y)+1) 

                        y += (doc.autoTable.previous.finalY - y) + 1
                        
                        // 
                        doc.autoTable({
                            html: '#page43', 
                            theme: "plain", 
                            startY: y+1,  
                            margin: { left: ((inches_1/5)*2)+1, right: ((inches_1/5)*2)+tableWidthCol2+3+tableWidthCol12, top: 0+1},
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles2, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 1, right: 1, top: 0.8, bottom: 0.8 }

                                data.cell.styles.textColor = [0, 0, 0]
                                data.cell.styles.cellPadding = defaultPadding
                                data.cell.styles.valign = 'middle'
                                data.cell.styles.halign = 'left'
                                data.cell.styles.fontSize = 8

                                if (data.row.index === 0) {
                                    data.cell.styles.halign = 'center'
                                    data.cell.styles.fontSize = 6
                                }
                                
                            }
                        })
                        doc.rect(((inches_1/5)*2), y, tableWidthCol1/2, (doc.autoTable.previous.finalY - y)+1) 
                        
                        // 
                        doc.autoTable({
                            html: '#page44', 
                            theme: "plain", 
                            startY: y+1,  
                            margin: { left: ((inches_1/5)*2)+3+tableWidthCol12, right: ((inches_1/5)*2)+3+50+(tableWidthCol12/2)+1, top: 0+1},
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            columnStyles: columnStyles2, 
                            didParseCell: function (data) {

                                defaultPadding = { left: 1, right: 1, top: 0.8, bottom: 0.8 }

                                data.cell.styles.textColor = [0, 0, 0]
                                data.cell.styles.cellPadding = defaultPadding
                                data.cell.styles.valign = 'middle'
                                data.cell.styles.halign = 'center'
                                data.cell.styles.fontSize = 6
                                
                                if (data.row.index === 0) {
                                    data.cell.styles.fontSize = 6
                                    data.cell.styles.cellPadding = { left: defaultPadding.left, right: defaultPadding.right, top: 0.8, bottom: 0.8 }
                                } 

                            }
                        })
                        temp_height += (doc.autoTable.previous.finalY - y + 1)
                        doc.rect(((inches_1/5)*2)+tableWidthCol12+2, y, tableWidthCol1/2, (doc.autoTable.previous.finalY - y)+1) 
                        
                        y += (doc.autoTable.previous.finalY - y) + 1
                        
                        // 
                        doc.rect(((inches_1/5)*2), temp_y, tableWidthMain, temp_height) 

                        // PHOTO
                        tableMargin = 18
                        doc.autoTable({
                            html: '#page45', 
                            theme: "plain", 
                            startY: temp_y+(tableMargin/2),  
                            margin: { left: ((inches_1/5)*2)+tableWidthCol1 + (tableMargin/2), right: ((inches_1/5)*2)+(tableMargin/2)},
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            didParseCell: function (data) {
                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }
                                data.cell.styles.fontSize = 6
                                data.cell.styles.halign = 'center'
                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) + 1

                        text = "PHOTO"
                        textWidth = doc.getTextWidth(text)
                        doc.text(text, (((inches_1/5)*2)+tableWidthCol1 + (tableWidthCol2/2) - (textWidth/2)), (temp_y + tableWidthCol2 - (tableMargin/2)))

                        y += (tableMargin/2)

                        doc.autoTable({
                            html: '#page46', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: ((inches_1/5)*2)+tableWidthCol1 + (tableMargin/4), right: ((inches_1/5)*2)+(tableMargin/4)},
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            didParseCell: function (data) {
                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }
                                data.cell.styles.fontSize = 6
                                data.cell.styles.halign = 'center'
                            }
                        })

                        y += (doc.autoTable.previous.finalY - y) + 1

                        // 
                        doc.rect(((inches_1/5)*2), y + 1, tableWidthMain, 40) 

                        y += 8

                        text = "SUBSCRIBED AND SWORN to before me this _______________________________________ , affiant exhibiting his/her validly issued government ID as indicated above."
                        textWidth = doc.getTextWidth(text)
                        doc.setFontSize(6)
                        doc.text(text, (((inches_1/5)*2) + 3), y)

                        y += 8

                        doc.autoTable({
                            html: '#page47', 
                            theme: "grid", 
                            startY: y,  
                            margin: { left: ((inches_1/5)*2)+(tableWidthMain/2) - (tableWidthCol2*1.5)/2, right: ((inches_1/5)*2)+(tableWidthMain/2) - (tableWidthCol2*1.5)/2},
                            styles: { 
                                cellPadding: 0.8, 
                                lineColor: [0, 0, 0], 
                                lineWidth: 0.3 
                            }, 
                            didParseCell: function (data) {
                                defaultPadding = { left: 0.8, right: 0.8, top: 0.8, bottom: 0.8 }
                                data.cell.styles.fontSize = 8
                                data.cell.styles.halign = 'center'
                            }
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

        (function() {
            generatePDF()
        })() 

    </script>
@endsection