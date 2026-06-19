@extends("dashboard.layouts.main")

@section("container")
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center shadow-lg">
            <i class="ri-translate-2 text-white text-2xl"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Smart Learning English</h1>
            <p class="text-sm text-gray-400">Latih pronunciation bahasa Inggris kamu</p>
        </div>
    </div>

    {{-- Best Score Cards --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        @foreach (['easy' => ['Mudah','emerald'], 'medium' => ['Sedang','amber'], 'hard' => ['Sulit','rose']] as $lvl => [$label, $color])
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-xs text-gray-400 mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold text-{{ $color }}-600">{{ $bestScore[$lvl] ?? '–' }}</p>
            <p class="text-[10px] text-gray-400">best score</p>
        </div>
        @endforeach
    </div>

    {{-- GAME AREA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Level Selector (tampil saat belum main) --}}
        <div id="level-screen" class="p-8 text-center">
            <div class="text-5xl mb-4">🎯</div>
            <h2 class="text-lg font-bold text-gray-800 mb-2">Pilih Tingkat Kesulitan</h2>
            <p class="text-sm text-gray-400 mb-6">Setiap sesi terdiri dari 10 kata/frasa secara acak</p>
            <div class="flex flex-col gap-3">
                <button onclick="startGame('easy')"
                    class="w-full flex items-center justify-between rounded-xl border-2 border-emerald-200 bg-emerald-50 px-5 py-4 hover:bg-emerald-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🟢</span>
                        <div class="text-left">
                            <p class="font-bold text-emerald-700">Mudah</p>
                            <p class="text-xs text-gray-400">Kata-kata dasar & sapaan</p>
                        </div>
                    </div>
                    <i class="ri-arrow-right-line text-emerald-500"></i>
                </button>
                <button onclick="startGame('medium')"
                    class="w-full flex items-center justify-between rounded-xl border-2 border-amber-200 bg-amber-50 px-5 py-4 hover:bg-amber-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🟡</span>
                        <div class="text-left">
                            <p class="font-bold text-amber-700">Sedang</p>
                            <p class="text-xs text-gray-400">Kosakata dunia kerja</p>
                        </div>
                    </div>
                    <i class="ri-arrow-right-line text-amber-500"></i>
                </button>
                <button onclick="startGame('hard')"
                    class="w-full flex items-center justify-between rounded-xl border-2 border-rose-200 bg-rose-50 px-5 py-4 hover:bg-rose-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🔴</span>
                        <div class="text-left">
                            <p class="font-bold text-rose-700">Sulit</p>
                            <p class="text-xs text-gray-400">Kata kompleks & idiom bisnis</p>
                        </div>
                    </div>
                    <i class="ri-arrow-right-line text-rose-500"></i>
                </button>
            </div>
            <p class="text-xs text-gray-300 mt-5">
                <i class="ri-chrome-line"></i> Fitur ini memerlukan browser Chrome / Edge
            </p>
        </div>

        {{-- Game Screen (tersembunyi awalnya) --}}
        <div id="game-screen" class="hidden p-6">

            {{-- Progress bar --}}
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs text-gray-400" id="progress-text">1 / 10</span>
                <span class="text-xs font-semibold text-indigo-600" id="current-score-text">Skor: 0</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-6">
                <div id="progress-bar" class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width:10%"></div>
            </div>

            {{-- Word Display --}}
            <div class="text-center mb-6">
                <p class="text-4xl font-bold text-gray-800 mb-2" id="word-display">—</p>
                <p class="text-sm text-gray-400 italic" id="phonetic-display"></p>
                <p class="text-sm text-indigo-600 font-medium mt-1" id="translation-display"></p>
                <p class="text-xs text-gray-400 mt-2" id="example-display"></p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 mb-5">
                <button id="btn-listen" onclick="speakWord()"
                    class="flex-1 flex items-center justify-center gap-2 rounded-xl border-2 border-teal-200 bg-teal-50 py-3 text-sm font-semibold text-teal-700 hover:bg-teal-100 transition-colors">
                    <i class="ri-volume-up-line text-lg"></i> Dengar
                </button>
                <button id="btn-speak" onclick="startRecognition()"
                    class="flex-1 flex items-center justify-center gap-2 rounded-xl border-2 border-indigo-200 bg-indigo-50 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors">
                    <i class="ri-mic-line text-lg"></i> Bicara
                </button>
            </div>

            {{-- Result area --}}
            <div id="result-area" class="hidden rounded-xl p-4 mb-4 text-center">
                <p class="text-2xl font-bold mb-1" id="result-score-display"></p>
                <p class="text-sm" id="result-text"></p>
                <p class="text-xs text-gray-400 mt-1">Kamu bilang: "<span id="result-heard"></span>"</p>
            </div>

            {{-- Next button --}}
            <button id="btn-next" onclick="nextWord()" class="hidden w-full rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                Lanjut <i class="ri-arrow-right-line"></i>
            </button>
        </div>

        {{-- Result Screen --}}
        <div id="result-screen" class="hidden p-8 text-center">
            <div class="text-6xl mb-4" id="final-emoji">🏆</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1" id="final-score-display">100</h2>
            <p class="text-sm text-gray-400 mb-1" id="final-detail"></p>
            <p class="text-xs text-gray-400 mb-6" id="final-level"></p>

            <div class="flex gap-3">
                <button onclick="restartGame()"
                    class="flex-1 rounded-xl border-2 border-indigo-200 bg-indigo-50 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors">
                    <i class="ri-refresh-line"></i> Main Lagi
                </button>
                <button onclick="showLevelScreen()"
                    class="flex-1 rounded-xl bg-indigo-600 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition-colors">
                    Ganti Level
                </button>
            </div>
        </div>

    </div>

    {{-- Leaderboard --}}
    @if($leaderboard->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-5 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="ri-trophy-line text-amber-500 text-lg"></i>
            <h3 class="font-bold text-gray-800 text-sm">Leaderboard</h3>
        </div>
        <ul class="divide-y divide-gray-50">
            @foreach($leaderboard as $i => $row)
            <li class="flex items-center gap-3 px-5 py-3">
                <span class="w-6 text-center text-xs font-bold {{ $i === 0 ? 'text-amber-500' : ($i === 1 ? 'text-gray-400' : ($i === 2 ? 'text-orange-400' : 'text-gray-300')) }}">
                    {{ $i + 1 }}
                </span>
                <img src="{{ $row->karyawan->foto ? asset('storage/unggah/karyawan/'.$row->karyawan->foto) : 'https://ui-avatars.com/api/?name='.urlencode($row->karyawan->nama_lengkap).'&background=6366f1&color=fff&size=32' }}"
                     class="w-8 h-8 rounded-full object-cover" alt="">
                <span class="flex-1 text-sm font-medium text-gray-700">{{ $row->karyawan->nama_lengkap }}</span>
                <span class="text-sm font-bold text-indigo-600">{{ $row->best_score }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection

@section("js")
<script>
    let words = [], currentIndex = 0, currentLevel = 'easy';
    let totalScore = 0, correctCount = 0;
    let recognition = null;

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    async function startGame(level) {
        currentLevel = level;
        currentIndex = 0;
        totalScore = 0;
        correctCount = 0;

        const res = await fetch(`/karyawan/learning/words?level=${level}`);
        words = await res.json();

        document.getElementById('level-screen').classList.add('hidden');
        document.getElementById('result-screen').classList.add('hidden');
        document.getElementById('game-screen').classList.remove('hidden');

        showWord();
    }

    function showWord() {
        const w = words[currentIndex];
        document.getElementById('word-display').textContent       = w.word;
        document.getElementById('phonetic-display').textContent   = w.phonetic ?? '';
        document.getElementById('translation-display').textContent= '🇮🇩 ' + w.translation;
        document.getElementById('example-display').textContent    = w.example ? '✏️ ' + w.example : '';
        document.getElementById('progress-text').textContent      = (currentIndex + 1) + ' / ' + words.length;
        document.getElementById('progress-bar').style.width       = ((currentIndex + 1) / words.length * 100) + '%';
        document.getElementById('current-score-text').textContent = 'Skor: ' + totalScore;
        document.getElementById('result-area').classList.add('hidden');
        document.getElementById('btn-next').classList.add('hidden');
        document.getElementById('btn-speak').disabled = false;
        document.getElementById('btn-speak').innerHTML = '<i class="ri-mic-line text-lg"></i> Bicara';
    }

    function speakWord() {
        if (!window.speechSynthesis) return;
        const utt = new SpeechSynthesisUtterance(words[currentIndex].word);
        utt.lang = 'en-US';
        utt.rate = 0.85;
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utt);
    }

    function startRecognition() {
        if (!SpeechRecognition) {
            alert('Browser kamu belum mendukung Speech Recognition. Gunakan Chrome atau Edge.');
            return;
        }
        const btn = document.getElementById('btn-speak');
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-record-circle-line text-lg animate-pulse text-red-500"></i> Mendengarkan...';

        recognition = new SpeechRecognition();
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 3;

        recognition.onresult = (e) => {
            const heard = e.results[0][0].transcript.trim().toLowerCase();
            gradeAnswer(heard);
        };

        recognition.onerror = () => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-mic-line text-lg"></i> Bicara';
        };

        recognition.onend = () => {
            btn.disabled = false;
        };

        recognition.start();
    }

    function gradeAnswer(heard) {
        const target = words[currentIndex].word.toLowerCase().trim();
        let points = 0;
        let resultHtml = '';

        if (heard === target) {
            points = 100; resultHtml = '🎉 Sempurna!';
        } else if (similarity(heard, target) >= 0.8) {
            points = 80; resultHtml = '👍 Hampir Tepat!';
        } else if (similarity(heard, target) >= 0.5) {
            points = 50; resultHtml = '😊 Lumayan!';
        } else {
            points = 0; resultHtml = '😅 Coba Lagi Nanti';
        }

        if (points >= 50) correctCount++;
        totalScore += points;

        const area = document.getElementById('result-area');
        area.className = points >= 80
            ? 'rounded-xl p-4 mb-4 text-center bg-green-50 border border-green-200'
            : points >= 50
                ? 'rounded-xl p-4 mb-4 text-center bg-amber-50 border border-amber-200'
                : 'rounded-xl p-4 mb-4 text-center bg-red-50 border border-red-200';

        document.getElementById('result-score-display').textContent = '+' + points + ' poin';
        document.getElementById('result-text').textContent = resultHtml;
        document.getElementById('result-heard').textContent = heard;
        area.classList.remove('hidden');
        document.getElementById('btn-next').classList.remove('hidden');
        document.getElementById('current-score-text').textContent = 'Skor: ' + totalScore;
    }

    function nextWord() {
        currentIndex++;
        if (currentIndex >= words.length) {
            showFinalResult();
        } else {
            showWord();
        }
    }

    async function showFinalResult() {
        const avgScore = Math.round(totalScore / words.length);
        document.getElementById('game-screen').classList.add('hidden');
        document.getElementById('result-screen').classList.remove('hidden');
        document.getElementById('final-score-display').textContent = avgScore + ' / 100';
        document.getElementById('final-detail').textContent = correctCount + ' dari ' + words.length + ' kata benar';
        document.getElementById('final-level').textContent = 'Level: ' + currentLevel.toUpperCase();
        document.getElementById('final-emoji').textContent =
            avgScore >= 90 ? '🏆' : avgScore >= 70 ? '🎯' : avgScore >= 50 ? '💪' : '📚';

        await fetch('/karyawan/learning/score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                level: currentLevel,
                score: avgScore,
                correct: correctCount,
                total: words.length
            })
        });
    }

    function restartGame() {
        document.getElementById('result-screen').classList.add('hidden');
        startGame(currentLevel);
    }

    function showLevelScreen() {
        document.getElementById('result-screen').classList.add('hidden');
        document.getElementById('game-screen').classList.add('hidden');
        document.getElementById('level-screen').classList.remove('hidden');
    }

    function similarity(a, b) {
        if (a === b) return 1;
        const longer = a.length > b.length ? a : b;
        const shorter = a.length > b.length ? b : a;
        if (longer.length === 0) return 1;
        return (longer.length - editDistance(longer, shorter)) / longer.length;
    }

    function editDistance(a, b) {
        const m = a.length, n = b.length;
        const dp = Array.from({length: m + 1}, (_, i) => Array.from({length: n + 1}, (_, j) => i === 0 ? j : j === 0 ? i : 0));
        for (let i = 1; i <= m; i++)
            for (let j = 1; j <= n; j++)
                dp[i][j] = a[i-1] === b[j-1] ? dp[i-1][j-1] : 1 + Math.min(dp[i-1][j], dp[i][j-1], dp[i-1][j-1]);
        return dp[m][n];
    }
</script>
@endsection
