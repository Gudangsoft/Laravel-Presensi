<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LearningWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $words = [
            // ── EASY ──────────────────────────────────────────────────────
            ['word' => 'Hello',        'translation' => 'Halo',               'phonetic' => '/həˈloʊ/',       'example' => 'Hello, how are you?',               'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Good morning', 'translation' => 'Selamat pagi',       'phonetic' => '/ɡʊd ˈmɔːrnɪŋ/','example' => 'Good morning, everyone!',           'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Good night',   'translation' => 'Selamat malam',      'phonetic' => '/ɡʊd naɪt/',    'example' => 'Good night, sleep well.',           'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Thank you',    'translation' => 'Terima kasih',       'phonetic' => '/θæŋk juː/',    'example' => 'Thank you for your help.',          'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Please',       'translation' => 'Tolong / Silakan',   'phonetic' => '/pliːz/',        'example' => 'Please help me.',                  'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Sorry',        'translation' => 'Maaf',               'phonetic' => '/ˈsɒri/',        'example' => 'Sorry for being late.',             'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Excuse me',    'translation' => 'Permisi',            'phonetic' => '/ɪkˈskjuːz miː/','example' => 'Excuse me, where is the office?',  'level' => 'easy', 'category' => 'greetings'],
            ['word' => 'Yes',          'translation' => 'Ya',                 'phonetic' => '/jɛs/',          'example' => 'Yes, I understand.',                'level' => 'easy', 'category' => 'basic'],
            ['word' => 'No',           'translation' => 'Tidak',              'phonetic' => '/noʊ/',          'example' => 'No, that is not correct.',          'level' => 'easy', 'category' => 'basic'],
            ['word' => 'Water',        'translation' => 'Air',                'phonetic' => '/ˈwɔːtər/',      'example' => 'May I have some water?',            'level' => 'easy', 'category' => 'daily'],
            ['word' => 'Food',         'translation' => 'Makanan',            'phonetic' => '/fuːd/',         'example' => 'The food is delicious.',            'level' => 'easy', 'category' => 'daily'],
            ['word' => 'Work',         'translation' => 'Bekerja / Pekerjaan','phonetic' => '/wɜːrk/',        'example' => 'I go to work every day.',           'level' => 'easy', 'category' => 'work'],
            ['word' => 'Office',       'translation' => 'Kantor',             'phonetic' => '/ˈɒfɪs/',        'example' => 'The office is on the second floor.','level' => 'easy', 'category' => 'work'],
            ['word' => 'Meeting',      'translation' => 'Rapat',              'phonetic' => '/ˈmiːtɪŋ/',      'example' => 'We have a meeting at ten.',         'level' => 'easy', 'category' => 'work'],
            ['word' => 'Computer',     'translation' => 'Komputer',           'phonetic' => '/kəmˈpjuːtər/',  'example' => 'I use a computer every day.',       'level' => 'easy', 'category' => 'technology'],
            ['word' => 'Phone',        'translation' => 'Telepon',            'phonetic' => '/foʊn/',         'example' => 'My phone is ringing.',              'level' => 'easy', 'category' => 'technology'],
            ['word' => 'Email',        'translation' => 'Surel',              'phonetic' => '/ˈiːmeɪl/',      'example' => 'Please send me an email.',          'level' => 'easy', 'category' => 'technology'],
            ['word' => 'Name',         'translation' => 'Nama',               'phonetic' => '/neɪm/',         'example' => 'What is your name?',                'level' => 'easy', 'category' => 'basic'],
            ['word' => 'Help',         'translation' => 'Bantuan',            'phonetic' => '/hɛlp/',         'example' => 'Can you help me, please?',          'level' => 'easy', 'category' => 'basic'],
            ['word' => 'Today',        'translation' => 'Hari ini',           'phonetic' => '/təˈdeɪ/',       'example' => 'Today is a great day.',             'level' => 'easy', 'category' => 'time'],

            // ── MEDIUM ─────────────────────────────────────────────────────
            ['word' => 'Attendance',   'translation' => 'Kehadiran',          'phonetic' => '/əˈtɛndəns/',    'example' => 'Your attendance is very good.',     'level' => 'medium', 'category' => 'work'],
            ['word' => 'Performance',  'translation' => 'Kinerja',            'phonetic' => '/pərˈfɔːrməns/', 'example' => 'Your performance is excellent.',    'level' => 'medium', 'category' => 'work'],
            ['word' => 'Deadline',     'translation' => 'Batas waktu',        'phonetic' => '/ˈdɛdlaɪn/',     'example' => 'The deadline is tomorrow.',         'level' => 'medium', 'category' => 'work'],
            ['word' => 'Schedule',     'translation' => 'Jadwal',             'phonetic' => '/ˈskɛdʒuːl/',    'example' => 'What is your schedule today?',      'level' => 'medium', 'category' => 'work'],
            ['word' => 'Department',   'translation' => 'Departemen',         'phonetic' => '/dɪˈpɑːrtmənt/', 'example' => 'Which department do you work in?',  'level' => 'medium', 'category' => 'work'],
            ['word' => 'Presentation', 'translation' => 'Presentasi',         'phonetic' => '/ˌprɛzənˈteɪʃn/','example' => 'The presentation was impressive.', 'level' => 'medium', 'category' => 'work'],
            ['word' => 'Responsibility','translation'=> 'Tanggung jawab',     'phonetic' => '/rɪˌspɒnsɪˈbɪlɪti/','example'=>'This is your responsibility.',    'level' => 'medium', 'category' => 'work'],
            ['word' => 'Achievement',  'translation' => 'Pencapaian',         'phonetic' => '/əˈtʃiːvmənt/',  'example' => 'Great achievement this month!',     'level' => 'medium', 'category' => 'motivation'],
            ['word' => 'Cooperation',  'translation' => 'Kerja sama',         'phonetic' => '/koʊˌɒpəˈreɪʃn/','example' => 'Cooperation is the key to success.','level' => 'medium', 'category' => 'teamwork'],
            ['word' => 'Communication','translation' => 'Komunikasi',         'phonetic' => '/kəˌmjuːnɪˈkeɪʃn/','example'=>'Good communication is essential.','level' => 'medium', 'category' => 'teamwork'],
            ['word' => 'Professional', 'translation' => 'Profesional',        'phonetic' => '/prəˈfɛʃənl/',   'example' => 'Stay professional at all times.',   'level' => 'medium', 'category' => 'work'],
            ['word' => 'Opportunity',  'translation' => 'Kesempatan',         'phonetic' => '/ˌɒpəˈtjuːnɪti/','example' => 'This is a great opportunity.',     'level' => 'medium', 'category' => 'motivation'],
            ['word' => 'Productivity', 'translation' => 'Produktivitas',      'phonetic' => '/ˌprɒdʌkˈtɪvɪti/','example'=>'Increase your productivity.',       'level' => 'medium', 'category' => 'work'],
            ['word' => 'Evaluation',   'translation' => 'Evaluasi',           'phonetic' => '/ɪˌvæljuˈeɪʃn/', 'example' => 'The annual evaluation is next week.','level' => 'medium', 'category' => 'work'],
            ['word' => 'Motivation',   'translation' => 'Motivasi',           'phonetic' => '/ˌmoʊtɪˈveɪʃn/', 'example' => 'Keep your motivation high.',        'level' => 'medium', 'category' => 'motivation'],
            ['word' => 'Leadership',   'translation' => 'Kepemimpinan',       'phonetic' => '/ˈliːdərʃɪp/',   'example' => 'Leadership is a vital skill.',      'level' => 'medium', 'category' => 'teamwork'],
            ['word' => 'Innovation',   'translation' => 'Inovasi',            'phonetic' => '/ˌɪnəˈveɪʃn/',   'example' => 'Innovation drives progress.',       'level' => 'medium', 'category' => 'work'],
            ['word' => 'Efficiency',   'translation' => 'Efisiensi',          'phonetic' => '/ɪˈfɪʃənsi/',    'example' => 'Work with efficiency.',             'level' => 'medium', 'category' => 'work'],
            ['word' => 'Commitment',   'translation' => 'Komitmen',           'phonetic' => '/kəˈmɪtmənt/',   'example' => 'Show your commitment to the team.', 'level' => 'medium', 'category' => 'teamwork'],
            ['word' => 'Improvement',  'translation' => 'Peningkatan',        'phonetic' => '/ɪmˈpruːvmənt/', 'example' => 'Continuous improvement is key.',    'level' => 'medium', 'category' => 'motivation'],

            // ── HARD ───────────────────────────────────────────────────────
            ['word' => 'Entrepreneurship','translation'=>'Kewirausahaan',     'phonetic' => '/ˌɒntrəprəˈnɜːʃɪp/','example'=>'Entrepreneurship requires courage.','level'=> 'hard', 'category' => 'business'],
            ['word' => 'Accountability','translation'=> 'Akuntabilitas',      'phonetic' => '/əˌkaʊntəˈbɪlɪti/','example'=>'Accountability builds trust.',     'level' => 'hard', 'category' => 'work'],
            ['word' => 'Perseverance',  'translation'=> 'Ketekunan',          'phonetic' => '/ˌpɜːrsɪˈvɪərəns/','example'=>'Perseverance leads to success.',   'level' => 'hard', 'category' => 'motivation'],
            ['word' => 'Collaboration', 'translation'=> 'Kolaborasi',         'phonetic' => '/kəˌlæbəˈreɪʃn/', 'example'=>'Collaboration makes us stronger.',  'level' => 'hard', 'category' => 'teamwork'],
            ['word' => 'Sustainability','translation'=> 'Keberlanjutan',      'phonetic' => '/səˌsteɪnəˈbɪlɪti/','example'=>'Sustainability is our priority.','level' => 'hard', 'category' => 'environment'],
            ['word' => 'Authenticity', 'translation'=> 'Keaslian',           'phonetic' => '/ɔːˌθɛntɪˈsɪti/', 'example'=>'Authenticity builds credibility.',  'level' => 'hard', 'category' => 'character'],
            ['word' => 'Resilience',   'translation'=> 'Ketahanan',          'phonetic' => '/rɪˈzɪliəns/',     'example'=>'Resilience helps us recover fast.', 'level' => 'hard', 'category' => 'character'],
            ['word' => 'Transparency', 'translation'=> 'Transparansi',       'phonetic' => '/trænsˈpærənsi/', 'example'=>'Transparency is vital in business.','level' => 'hard', 'category' => 'business'],
            ['word' => 'Integrity',    'translation'=> 'Integritas',          'phonetic' => '/ɪnˈtɛɡrɪti/',    'example'=>'Always work with integrity.',       'level' => 'hard', 'category' => 'character'],
            ['word' => 'Sophisticated','translation'=> 'Canggih / Kompleks',  'phonetic' => '/səˈfɪstɪkeɪtɪd/','example'=>'This is a sophisticated system.',   'level' => 'hard', 'category' => 'vocabulary'],
            ['word' => 'Prioritization','translation'=>'Penetapan prioritas', 'phonetic' => '/praɪˌɒrɪtaɪˈzeɪʃn/','example'=>'Prioritization saves time.',   'level' => 'hard', 'category' => 'work'],
            ['word' => 'Multitasking', 'translation'=> 'Mengerjakan banyak hal sekaligus','phonetic'=> '/ˌmʌltɪˈtɑːskɪŋ/','example'=>'Multitasking requires focus.','level'=> 'hard', 'category' => 'work'],
            ['word' => 'Organizational','translation'=>'Organisasional',      'phonetic' => '/ˌɔːɡənəˈzeɪʃənl/','example'=>'Build strong organizational skills.','level'=> 'hard', 'category' => 'work'],
            ['word' => 'Simultaneously','translation'=>'Secara bersamaan',    'phonetic' => '/ˌsɪməlˈteɪniəsli/','example'=>'Handle tasks simultaneously.',    'level' => 'hard', 'category' => 'vocabulary'],
            ['word' => 'Comprehensive','translation'=> 'Komprehensif',        'phonetic' => '/ˌkɒmprɪˈhɛnsɪv/','example'=>'Write a comprehensive report.',    'level' => 'hard', 'category' => 'vocabulary'],
        ];

        foreach ($words as $word) {
            \App\Models\LearningWord::firstOrCreate(['word' => $word['word']], $word);
        }
    }
}
