<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
How to use? Call from controller
--------------------------------
$jurnalRefs = array(
    'ref_id' => ?,
    'ref_nomor' => ?,
    'ref_source' => ?
);
$jurnalAkunIds = array(1, 2); // ref to jurnal_akun table
$jurnalSaldo = 0;
jurnalGenerate($jurnalRefs, $jurnalAkunIds, $jurnalSaldo);
 */
if (!function_exists('jurnalGenerate')) {
    function jurnalGenerate($refs = array(), $jurnalAkunIds = array(), $saldo = 0)
    {
        $ci = &get_instance();
        $ci->load->database();

        if (count($jurnalAkunIds) > 0) {
            foreach ($jurnalAkunIds as $akunId) {
                $akun = $ci->db->where(array('id' => $akunId))->get('jurnal_akun')->row();

                if (!is_null($akun)) {
                    $payload = array(
                        'jurnal_akun_id' => $akunId,
                        'keterangan' => $akun->nama,
                        'jenis' => $akun->jenis,
                        'saldo' => preg_replace('/[^0-9]/', '', $saldo),
                        'tanggal' => date('Y-m-d'),
                        'ref_id' => isset($refs['ref_id']) ? $refs['ref_id'] : null,
                        'ref_nomor' => isset($refs['ref_nomor']) ? $refs['ref_nomor'] : null,
                        'ref_source' => isset($refs['ref_source']) ? $refs['ref_source'] : null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $ci->session->userdata('user')['id']
                    );
                    $ci->db->insert('jurnal', $payload);
                };
            };
        };
    }
}

if (!function_exists('jurnalGenerateStatic')) {
    function jurnalGenerateStatic($refs = array(), $jurnalPayload = array())
    {
        $ci = &get_instance();
        $ci->load->database();

        try {
            if (count($jurnalPayload) > 0) {
                foreach ($jurnalPayload as $item) {
                    $akunId = $item->jurnal_akun_id;
                    $akun = $ci->db->where(array('id' => $akunId))->get('jurnal_akun')->row();

                    if (!is_null($akun)) {
                        $payload = array(
                            'jurnal_akun_id' => $akunId,
                            'keterangan' => (isset($item->keterangan)) ? $item->keterangan : $akun->nama,
                            'jenis' => $akun->jenis,
                            'saldo' => preg_replace('/[^0-9.]/', '', $item->saldo),
                            'tanggal' => date('Y-m-d'),
                            'ref_id' => isset($refs['ref_id']) ? $refs['ref_id'] : null,
                            'ref_nomor' => isset($refs['ref_nomor']) ? $refs['ref_nomor'] : null,
                            'ref_source' => isset($refs['ref_source']) ? $refs['ref_source'] : null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $ci->session->userdata('user')['id']
                        );
                        $ci->db->insert('jurnal', $payload);
                    };
                };
            };
            return true;
        } catch (\Throwable $th) {
            return false;
        };
    }
}

if (!function_exists('jurnalUpdateRef')) {
    function jurnalUpdateRef($refs = array(), $jurnalAkunId = null)
    {
        $ci = &get_instance();
        $ci->load->database();

        $akun = $ci->db->where(array('id' => $jurnalAkunId))->get('jurnal_akun')->row();

        if (!is_null($akun)) {
            $payload = array(
                'ref_id' => isset($refs['ref_id']) ? $refs['ref_id'] : null,
                'ref_nomor' => isset($refs['ref_nomor']) ? $refs['ref_nomor'] : null,
                'ref_source' => isset($refs['ref_source']) ? $refs['ref_source'] : null
            );
            $ci->db->update('jurnal', $payload, array(
                'jurnal_akun_id' => $jurnalAkunId,
                'ref_id' => isset($refs['old_ref_id']) ? $refs['old_ref_id'] : null,
                'ref_nomor' => isset($refs['old_ref_nomor']) ? $refs['old_ref_nomor'] : null,
                'ref_source' => isset($refs['old_ref_source']) ? $refs['old_ref_source'] : null
            ));
        };
    }
}
