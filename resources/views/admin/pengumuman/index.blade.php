@extends("admin.layouts.app")

@section("container")
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengumuman</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengumuman yang tampil di dashboard karyawan</p>
        </div>
        <button onclick="openAddModal()"
                class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:-translate-y-0.5 transition-all"
                style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
            <i class="ri-add-line text-base"></i> Tambah Pengumuman
        </button>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <i class="ri-checkbox-circle-fill text-lg flex-shrink-0"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="ri-error-warning-fill text-lg flex-shrink-0"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-8">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Pin</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Dibuat</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($pengumuman as $i => $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $pengumuman->firstItem() + $i }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-800">{{ $p->judul }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $p->isi }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="toggleActive({{ $p->id }}, this)"
                                        data-active="{{ $p->is_active ? '1' : '0' }}"
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold transition-colors
                                               {{ $p->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    <i class="{{ $p->is_active ? 'ri-eye-line' : 'ri-eye-off-line' }}"></i>
                                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                @if ($p->is_pinned)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2.5 py-1 text-xs font-semibold">
                                        <i class="ri-pushpin-fill"></i> Disematkan
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openEditModal({{ json_encode(["id"=>$p->id,"judul"=>$p->judul,"isi"=>$p->isi,"is_pinned"=>$p->is_pinned]) }})'
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                                        <i class="ri-pencil-line text-sm"></i>
                                    </button>
                                    <button onclick="deletePengumuman({{ $p->id }})"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-300">
                                <i class="ri-megaphone-line text-4xl block mb-2"></i>
                                <p class="text-sm font-medium text-gray-400">Belum ada pengumuman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pengumuman->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ===== ADD MODAL ===== --}}
<div id="addModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none!important;">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddModal()"></div>
    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">Tambah Pengumuman</h3>
            <button onclick="closeAddModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ri-close-line text-gray-500"></i>
            </button>
        </div>
        <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul</label>
                <input type="text" name="judul" required placeholder="Judul pengumuman..."
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Isi</label>
                <textarea name="isi" required rows="5" placeholder="Isi pengumuman..."
                          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1"
                       class="h-4 w-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                <span class="text-sm text-gray-700">Sematkan pengumuman ini di bagian atas</span>
            </label>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddModal()"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition-all hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== EDIT MODAL ===== --}}
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none!important;">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">Edit Pengumuman</h3>
            <button onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ri-close-line text-gray-500"></i>
            </button>
        </div>
        <form action="{{ route('admin.pengumuman.update') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            <input type="hidden" name="id" id="editId" />
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul</label>
                <input type="text" name="judul" id="editJudul" required
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Isi</label>
                <textarea name="isi" id="editIsi" required rows="5"
                          class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-colors resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_pinned" id="editPinned" value="1"
                       class="h-4 w-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                <span class="text-sm text-gray-700">Sematkan pengumuman ini di bagian atas</span>
            </label>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition-all hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section("js")
<script>
    const addModal  = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    function openAddModal()  { addModal.style.setProperty('display','flex','important'); }
    function closeAddModal() { addModal.style.setProperty('display','none','important'); }
    function openEditModal(data) {
        document.getElementById('editId').value    = data.id;
        document.getElementById('editJudul').value = data.judul;
        document.getElementById('editIsi').value   = data.isi;
        document.getElementById('editPinned').checked = data.is_pinned;
        editModal.style.setProperty('display','flex','important');
    }
    function closeEditModal() { editModal.style.setProperty('display','none','important'); }

    function toggleActive(id, btn) {
        fetch('{{ route('admin.pengumuman.toggle') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id })
        }).then(r => r.json()).then(data => {
            btn.dataset.active = data.is_active ? '1' : '0';
            btn.className = btn.className.replace(/bg-\w+-100 text-\w+-\d+/g, '');
            if (data.is_active) {
                btn.classList.add('bg-emerald-100','text-emerald-700');
                btn.innerHTML = '<i class="ri-eye-line"></i> Aktif';
            } else {
                btn.classList.add('bg-gray-100','text-gray-500');
                btn.innerHTML = '<i class="ri-eye-off-line"></i> Nonaktif';
            }
        });
    }

    function deletePengumuman(id) {
        Swal.fire({
            title: 'Hapus Pengumuman?',
            text: 'Pengumuman akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
        }).then(result => {
            if (!result.isConfirmed) return;
            fetch('{{ route('admin.pengumuman.delete') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id })
            }).then(r => r.json()).then(data => {
                if (data.success) location.reload();
            });
        });
    }
</script>
@endsection
