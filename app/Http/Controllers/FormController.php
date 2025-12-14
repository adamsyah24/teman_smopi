<?php

namespace App\Http\Controllers;

use App\Helpers\WaBlast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi data jika diperlukan
        $admin = User::whereIn('role_id', [1, 2, 4])->get();
        $nomorWa = $admin->pluck('NOMOR_WA')->toArray();
        $tiket = DB::table('t_laporan_admin')->max('TIKET') + 1;

        $id = Str::uuid()->toString();
        $namaPengadu = $request->input('nama');
        $nohp = $request->input('nohp');
        $asal_instansi = $request->input('asal_instansi');
        $nama_di = $request->input('nama_di');
        $nama_instansi = $request->input('nama_instansi');
        $pengajar = $request->input('pengajar');
        $nama_akun = $request->input('nama_akun');
        $jenis_akun = $request->input('jenis_akun');
        $menu_kendala = $request->input('menu_kendala');
        $deskripsi = $request->input('deskripsi');
        // $bukti = $request->file('bukti')->store('bukti_pengaduan', 'public');
        $bukti = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Folder tujuan di website utama (bukan di BK)
            $target = '/home/irigasi/public_html/TEMAN-SMOPI/uploads/bukti_pengaduan';

            // Pastikan folder ada
            if (!file_exists($target)) {
                mkdir($target, 0755, true);
            }

            // Pindahkan file ke sana
            $file->move($target, $filename);

            // Simpan path relatif untuk URL
            $bukti = 'uploads/bukti_pengaduan/' . $filename;
        }
        $numbers = [];

        if (!empty($nohp)) {
            $numbers[] = $nohp;
        }

        if (!empty($nomorWa)) {
            $numbers = array_merge($numbers, (array) $nomorWa);
        }

        if (empty($nama_di)) {
            $nama_di = $nama_instansi;
        }

        try {
            DB::insert('INSERT INTO t_laporan_admin (ID, TIKET, NAMA, ID_KATEGORI,
            ASAL_INSTANSI, NAMA_DI, ID_PENGAJAR, NAMA_AKUN, JENIS_AKUN, DESKRIPSI
            , STATUS, BUKTI_SS, NO_HP, CREATED_AT, CREATED_BY, UPDATED_AT, UPDATED_BY) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $tiket,
                $namaPengadu,
                $menu_kendala,
                $asal_instansi,
                $nama_di,
                $pengajar,
                $nama_akun,
                $jenis_akun,
                $deskripsi,
                1,
                $bukti,
                $nohp,
                now(),
                'system',
                now(),
                'system',
            ]);

            $message = "📢 Laporan Baru!\n\n" .
                "Laporan baru telah masuk untuk diajukan dengan nomor tiket *$tiket*.\n" .
                "Terima kasih 🙏";
            // dd($message);

            WaBlast::send($numbers, $message);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ]);
        }
    }


    public function showForm()
    {
        $kategori = DB::table('ms_kategori')->get();
        $users = DB::table('users')
            ->where('role_name', "=", "Pengajar")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('form', compact('kategori', 'users'));
    }

    public function hapus(Request $request)
    {
        $id = $request->query('ID');

        $data = DB::table('t_laporan_admin')->where('ID', $id)->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan.');
        }

        DB::table('t_laporan_admin')
            ->where('ID', $id)
            ->update([
                'STATUS' => 0,
                'UPDATED_AT' => now(),
                'UPDATED_BY' => 'admin', // atau auth()->user()->name jika sudah login
            ]);

        return redirect()->back()->with('success', 'Data berhasil di-nonaktifkan.');
    }
}
