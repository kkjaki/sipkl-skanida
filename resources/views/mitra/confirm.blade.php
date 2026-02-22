@extends('layouts.mitra')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface overflow-hidden">
        <div class="border-b border-gray-200 dark:border-amoled-border py-6 px-8 bg-gray-50/50 dark:bg-white/[0.02]">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Formulir Kesanggupan & Data Sertifikat PKL
            </h2>
            <p class="text-sm text-school-blue dark:text-blue-400 font-medium mt-1">
                {{ $industry->name }}
            </p>
        </div>

        <div class="p-8">
            <div class="mb-8 p-4 rounded-xl bg-blue-500/5 border border-blue-500/10">
                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                    <span class="font-bold text-blue-600 dark:text-blue-400">Pesan:</span> 
                    Silakan lengkapi data penanggung jawab (PIC) yang akan menandatangani sertifikat siswa dan tentukan kuota kesanggupan penerimaan siswa PKL untuk tahun ajaran {{ $activeYear->name }}.
                </p>
            </div>

            <form action="{{ URL::signedRoute('mitra.update', $industry->id) }}" method="POST" 
                  x-data="{ 
                      quotas: {
                          @foreach($departments as $dept)
                            '{{ $dept->id }}': {{ old('quotas.' . $dept->id, $existingQuotas[$dept->id] ?? 0) }},
                          @endforeach
                      },
                      get totalQuota() {
                          return Object.values(this.quotas).reduce((a, b) => (parseInt(a) || 0) + (parseInt(b) || 0), 0);
                      },
                      handleSubmit(e) {
                          if (this.totalQuota <= 0) {
                              alert('Maaf, total kuota kesanggupan harus lebih dari 0.');
                              e.preventDefault();
                              return;
                          }
                          if (!confirm('Apakah Anda yakin data yang diisi sudah benar? Setelah dikirim, data tidak dapat diubah lagi.')) {
                              e.preventDefault();
                          }
                      }
                  }"
                  @submit="handleSubmit"
                  class="space-y-8">
                @csrf
                @method('PUT')

                @error('quotas')
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-sm font-medium">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Section: Data PIC -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-4 bg-school-blue rounded-full"></div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Data Penanggung Jawab (PIC)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <div>
                            <label for="pic_name" class="mb-1.5 block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Nama Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name', $industry->pic_name) }}" placeholder="Nama Lengkap & Gelar (jika ada)" required 
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                            @error('pic_name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="pic_position" class="mb-1.5 block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" id="pic_position" name="pic_position" value="{{ old('pic_position', $industry->pic_position) }}" placeholder="cth. Direktur / HRD Manager" required
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                            @error('pic_position')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="nip" class="mb-1.5 block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">NIP <span class="text-red-500">*</span></label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip', $industry->nip) }}" placeholder="Nomor Induk Pegawai" required
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                            @error('nip')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Kuota Kesanggupan -->
                <div class="pt-2">
                    <div class="flex items-center justify-between gap-4 mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Kuota Kesanggupan PKL</h3>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Total:</span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="totalQuota"></span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-4 italic">Isi dengan jumlah siswa yang dapat diterima untuk masing-masing program keahlian.</p>
                    
                    <div class="space-y-4">
                        @foreach($departments as $dept)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-xl border border-gray-100 dark:border-white/[0.05] bg-gray-50/30 dark:bg-white/[0.01]">
                                <label for="quota_{{ $dept->id }}" class="flex-grow">
                                    <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">{{ $dept->name }}</span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-600 font-medium uppercase tracking-widest">{{ $dept->code }}</span>
                                </label>
                                <div class="w-full sm:w-28 relative">
                                    <input type="number" id="quota_{{ $dept->id }}" name="quotas[{{ $dept->id }}]" 
                                           x-model.number="quotas['{{ $dept->id }}']" min="0" required
                                           class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue text-center pr-10" />
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-300 dark:text-gray-700 pointer-events-none">Siswa</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-3 rounded-xl bg-school-blue py-4 px-6 text-sm font-bold text-white hover:bg-school-blue/90 hover:shadow-lg hover:shadow-school-blue/20 transition-all duration-200 group">
                        <span>Simpan Konfirmasi</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <p class="text-[10px] text-center text-gray-400 dark:text-gray-500 mt-4 leading-relaxed">
                        Dengan menekan tombol di atas, Anda menyatakan bahwa data yang diberikan telah benar dan dapat dipertanggungjawabkan.
                    </p>
                </div>
            </form>

        </div>
    </div>
@endsection
