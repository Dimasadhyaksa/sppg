SPPG Demo (PHP Native + Supabase + Tailwind)
============================================

Cara pakai:
1. Extract zip ke folder, misal: C:\Users\HP\sppg-project
2. Edit file includes/supabase.php -> ganti SUPABASE_URL dan SUPABASE_KEY
3. Buat tabel di Supabase:
   - menu:
     id: int8 (Primary Key, auto-increment)
     judul: text
     kategori: text
     status: text
     catatan: text (optional)
     created_at: timestamptz (optional)
   - estimasi:
     id: int8 (PK)
     menu_id: int8
     estimasi: numeric
     keterangan: text
4. Jalankan server:
   php -S localhost:8000
5. Buka http://localhost:8000 dan login (admin / 123)
