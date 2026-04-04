<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMemberAndProfitSupport extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('member')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'no_member' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 120,
                ],
                'alamat' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'no_hp' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('no_member');
            $this->forge->createTable('member');
        }

        $penjualanFields = [];

        if (! $this->db->fieldExists('member_id', 'penjualan')) {
            $penjualanFields['member_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'user_id',
            ];
        }

        if (! $this->db->fieldExists('member_no', 'penjualan')) {
            $penjualanFields['member_no'] = [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'member_id',
            ];
        }

        if (! $this->db->fieldExists('member_nama', 'penjualan')) {
            $penjualanFields['member_nama'] = [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'member_no',
            ];
        }

        if (! $this->db->fieldExists('subtotal_kotor', 'penjualan')) {
            $penjualanFields['subtotal_kotor'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'member_nama',
            ];
        }

        if (! $this->db->fieldExists('diskon_type', 'penjualan')) {
            $penjualanFields['diskon_type'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'subtotal_kotor',
            ];
        }

        if (! $this->db->fieldExists('diskon_input', 'penjualan')) {
            $penjualanFields['diskon_input'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'diskon_type',
            ];
        }

        if (! $this->db->fieldExists('diskon_nominal', 'penjualan')) {
            $penjualanFields['diskon_nominal'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'diskon_input',
            ];
        }

        if (! $this->db->fieldExists('total_modal', 'penjualan')) {
            $penjualanFields['total_modal'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'diskon_nominal',
            ];
        }

        if (! empty($penjualanFields)) {
            $this->forge->addColumn('penjualan', $penjualanFields);
        }

        $detailFields = [];

        if (! $this->db->fieldExists('nama_produk', 'penjualan_detail')) {
            $detailFields['nama_produk'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'product_id',
            ];
        }

        if (! $this->db->fieldExists('kode_produk', 'penjualan_detail')) {
            $detailFields['kode_produk'] = [
                'type'       => 'VARCHAR',
                'constraint' => 60,
                'null'       => true,
                'after'      => 'nama_produk',
            ];
        }

        if (! $this->db->fieldExists('harga_beli', 'penjualan_detail')) {
            $detailFields['harga_beli'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'kode_produk',
            ];
        }

        if (! $this->db->fieldExists('harga_jual', 'penjualan_detail')) {
            $detailFields['harga_jual'] = [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0,
                'after'      => 'harga_beli',
            ];
        }

        if (! empty($detailFields)) {
            $this->forge->addColumn('penjualan_detail', $detailFields);
        }
    }

    public function down()
    {
        $detailColumns = ['nama_produk', 'kode_produk', 'harga_beli', 'harga_jual'];
        foreach ($detailColumns as $column) {
            if ($this->db->fieldExists($column, 'penjualan_detail')) {
                $this->forge->dropColumn('penjualan_detail', $column);
            }
        }

        $penjualanColumns = [
            'member_id',
            'member_no',
            'member_nama',
            'subtotal_kotor',
            'diskon_type',
            'diskon_input',
            'diskon_nominal',
            'total_modal',
        ];

        foreach ($penjualanColumns as $column) {
            if ($this->db->fieldExists($column, 'penjualan')) {
                $this->forge->dropColumn('penjualan', $column);
            }
        }

        if ($this->db->tableExists('member')) {
            $this->forge->dropTable('member');
        }
    }
}
