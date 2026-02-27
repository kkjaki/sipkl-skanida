<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat PKL</title>
    <style>
        @page {
            margin: 0px;
            size: A4 landscape;
        }

        body {
            margin: 0px;
            padding: 0px;
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            font-size: 18px;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* ===== Content Area ===== */
        .content {
            position: absolute;
            top: 250px;
            left: 75px;
            right: 75px;
            z-index: 1;
        }

        /* Nomor Surat */
        .text-nomor {
            text-align: center;
            margin-bottom: 0;
        }

        /* Diberikan Kepada */
        .text-diberikan {
            text-align: center;
            margin-top: 24px;
        }

        /* Tabel Data Siswa */
        .data-table {
            margin: 24px 0 0 80px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 1px 2x;
            vertical-align: top;
        }

        .data-table .label {
            width: 220px;
        }

        .data-table .separator {
            width: 10px;
            text-align: center;
        }

        /* Paragraf Keterangan */
        .text-keterangan {
            text-align: center;
            margin-top: 24px;
            padding: 0 40px;
            line-height: 1.25
        }

        /* ===== Signature Area ===== */
        .signature-area {
            position: absolute;
            bottom: 45px;
            left: 75px;
            right: 75px;
            z-index: 1;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            vertical-align: top;
            padding: 0;
        }

        .sig-left {
            width: 50%;
            text-align: center;
        }

        .sig-right {
            width: 50%;
            text-align: center;
        }

        .sig-spacer {
            height: 60px;
        }
    </style>
</head>
<body>
    @foreach($certificates as $cert)
        @php
            $student = $cert->internship->student;
            $industry = $cert->internship->industry;
            $internship = $cert->internship;
            $ttl = $student->place_of_birth . ', ' . \Carbon\Carbon::parse($student->date_of_birth)->locale('id')->translatedFormat('d F Y');
            $startDate = \Carbon\Carbon::parse($internship->start_date)->locale('id')->translatedFormat('d F Y');
            $endDate = $internship->actual_end_date
                ? \Carbon\Carbon::parse($internship->actual_end_date)->locale('id')->translatedFormat('d F Y')
                : '-';
            $issuedDate = \Carbon\Carbon::parse($cert->issued_date)->locale('id')->translatedFormat('d F Y');
        @endphp
        <div class="page">
            {{-- Background Image --}}
            <img src="{{ public_path('images/certificate-background.png') }}" class="bg-img" />

            {{-- Main Content --}}
            <div class="content">
                {{-- Nomor Surat --}}
                <div class="text-nomor">
                    Nomor : {{ $cert->certificate_number }}
                </div>

                {{-- Diberikan Kepada --}}
                <div class="text-diberikan">
                    Diberikan Kepada:
                </div>

                {{-- Tabel Data Siswa --}}
                <table class="data-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td style="text-transform: uppercase;">{{ $student->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="separator">:</td>
                        <td>{{ $ttl }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nomor Induk Siswa</td>
                        <td class="separator">:</td>
                        <td>{{ $student->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kelas/Program Keahlian</td>
                        <td class="separator">:</td>
                        <td>{{ $student->class_name ?? '-' }}</td>
                    </tr>
                </table>

                {{-- Paragraf Keterangan --}}
                <div class="text-keterangan">
                    Telah menyelesaikan Praktik Kerja Lapangan (PKL) di {{ strtoupper($industry->name ?? '-') }}
                    <br>
                    tanggal {{ $startDate }} &ndash; {{ $endDate }} dengan hasil : Baik / Amat Baik
                </div>
            </div>

            {{-- Signature Area --}}
            <div class="signature-area">
                {{-- Kota & Tanggal (rata kanan) --}}
                <div style="text-align: right; padding-right: 118px; margin-bottom: 12px;">
                    Magelang, {{ $issuedDate }}
                </div>

                {{-- Dua kolom tanda tangan --}}
                <table class="signature-table">
                    <tr>
                        <td class="sig-left">
                            {{ $industry->pic_position ?? 'Direktur' }}
                            <br>
                            {{ strtoupper($industry->name ?? '-') }}
                        </td>
                        <td class="sig-right">
                            Kepala SMK Negeri 2 Magelang
                        </td>
                    </tr>
                    <tr>
                        <td class="sig-left">
                            <div class="sig-spacer"></div>
                            {{ $industry->pic_name ?? '-' }}
                            @if($industry->nip)
                                <br>NIP. {{ $industry->nip }}
                            @endif
                        </td>
                        <td class="sig-right">
                            <div class="sig-spacer"></div>
                            Kurniawan Basuki, S.Pd., M.T
                            <br>
                            NIP. 19670929 199003 1 013
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>
</html>
