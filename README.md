 <h1>Internship Management Platform</h1>
    <p>This web application allows an educational institution to manage internships, students, supervisors, and employers.</p>

   <div class="section">
        <h2>Main Features</h2>
        <ul>
            <li>Multi-role authentication: admin, student, employer</li>
            <li>Student management: create, edit, delete, assign to employer and supervisor</li>
            <li>Supervisor management: create, edit, delete, assign to students</li>
            <li>Employer management: create, edit, delete, assign to students</li>
            <li>Progress reports: students submit, supervisors/admins review</li>
            <li>Evaluations: submit and view internship and company evaluations</li>
            <li>Google Maps: link employers to a location</li>
            <li>Secure logout and session management</li>
        </ul>
    </div>

  <div class="section">
        <h2>Project Structure</h2>
        <ul>
            <li><b>index.php</b>: Login page</li>
            <li><b>page_admin.php</b>: Admin dashboard</li>
            <li><b>admin_stagiaire.php</b>: Manage students</li>
            <li><b>admin_superviseur.php</b>: Manage supervisors</li>
            <li><b>admin_employeur.php</b>: Manage employers</li>
            <li><b>admin_associer_multiple.php</b>: Assign student ↔ employer ↔ supervisor</li>
            <li><b>admin_associer_stage.php</b>: Assign student ↔ employer</li>
            <li><b>admin_associer_superviseur.php</b>: Assign student ↔ supervisor</li>
            <li><b>admin_googlemap.php</b>: Manage Google Maps links for employers</li>
            <li><b>page_etudiant.php</b>: Student progress report entry</li>
            <li><b>rapport_etape.php</b>: View student progress report</li>
            <li><b>rapport_etape_modifier.php</b>: Edit progress report (admin)</li>
            <li><b>liste_des_stagiaires.php</b>: List of students and documents</li>
            <li><b>supprimer_etu.php</b>, <b>supprimer_superviseur.php</b>, <b>supprimer_employeur.php</b>, <b>supprimer_documents.php</b>: Secure deletion</li>
            <li><b>bd.php</b>: MySQL database connection</li>
            <li><b>css/</b>: Stylesheets</li>
            <li><b>Reference/</b>: Test login information</li>
        </ul>
    </div>

  <div class="section">
        <h2>Installation</h2>
        <ol>
            <li><b>Requirements:</b> Web server (Apache, Nginx), PHP 7.x+, MySQL/MariaDB</li>
            <li><b>Database:</b> Import the structure and data into your MySQL database. Update credentials in <code>bd.php</code> if needed.</li>
            <li><b>Email:</b> Configure SMTP in <code>php.ini</code> for notifications.</li>
            <li><b>Deployment:</b> Place all files in your web server root. Make <code>evaluations/</code> and <code>experiences/</code> writable if you want to generate HTML files.</li>
        </ol>
    </div>

  <div class="section">
        <h2>Usage</h2>
        <ul>
            <li>Go to <code>index.php</code> to log in.</li>
            <li>Use test credentials from <code>Reference/logins.txt</code>.</li>
            <li>Navigate according to your role to manage students, supervisors, employers, reports, and evaluations.</li>
        </ul>
    </div>

   <div class="section">
        <h2>Security</h2>
        <ul>
            <li>Access is protected by PHP sessions.</li>
            <li>Critical operations use prepared statements to prevent SQL injection.</li>
            <li>Deletions require confirmation and are admin-only.</li>
        </ul>
    </div>

   <div class="section">
        <h2>Authors</h2>
        <p>Developed by Armando (2024).</p>
    </div>

   <hr>
    <p><b>Note:</b> For questions or improvements, contact the project administrator.</p>
