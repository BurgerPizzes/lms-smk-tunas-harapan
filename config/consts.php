<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    |
    | Define the available roles in the LMS system.
    |
    */

    'roles' => [
        'admin'     => 'Admin',
        'guru'      => 'Guru',
        'siswa'     => 'Siswa',
    ],

    /*
    |--------------------------------------------------------------------------
    | Subject Categories (Kelompok Mata Pelajaran - Kurikulum Merdeka)
    |--------------------------------------------------------------------------
    |
    | NORMATIF  - Mata Pelajaran Normatif (Pendidikan Agama, PKn, Bahasa Indonesia)
    | ADAPTIF   - Mata Pelajaran Adaptif (Matematika, Bahasa Inggris, IPA/IPS)
    | PRODUKTIF - Mata Pelajaran Produktif (Kompetensi Keahlian)
    |
    */

    'subject_categories' => [
        'normatif'  => 'Normatif',
        'adaptif'   => 'Adaptif',
        'produktif' => 'Produktif',
    ],

    /*
    |--------------------------------------------------------------------------
    | Submission Status (Status Pengumpulan Tugas)
    |--------------------------------------------------------------------------
    */

    'submission_status' => [
        'submitted'     => 'Sudah Dikumpulkan',
        'late'          => 'Terlambat',
        'not_submitted' => 'Belum Dikumpulkan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Attendance Status (Status Kehadiran)
    |--------------------------------------------------------------------------
    */

    'attendance_status' => [
        'hadir'  => 'Hadir',
        'izin'   => 'Izin',
        'sakit'  => 'Sakit',
        'alpha'  => 'Alpha',
    ],

    /*
    |--------------------------------------------------------------------------
    | Grade Levels (Tingkat Kelas)
    |--------------------------------------------------------------------------
    */

    'grade_levels' => [
        'X'  => 'Kelas X',
        'XI' => 'Kelas XI',
        'XII' => 'Kelas XII',
    ],

    /*
    |--------------------------------------------------------------------------
    | Academic Terms (Tahun Ajaran)
    |--------------------------------------------------------------------------
    */

    'semesters' => [
        'ganjil' => 'Semester Ganjil',
        'genap'  => 'Semester Genap',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Limits
    |--------------------------------------------------------------------------
    */

    'upload' => [
        'max_file_size'      => 10240, // 10MB in KB
        'max_avatar_size'    => 2048,  // 2MB
        'max_material_size'  => 51200, // 50MB
        'max_submission_size'=> 10240, // 10MB
        'allowed_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'zip', 'rar'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'default'     => 15,
        'admin'       => 25,
        'table'       => 10,
        'student_list'=> 50,
    ],

];
