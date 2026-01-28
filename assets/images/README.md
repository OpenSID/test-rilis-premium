# Assets Images

Folder ini berisi gambar-gambar yang digunakan dalam aplikasi.

## Struktur

- `logo.png` - Logo aplikasi (letakkan file logo.png di sini)
- `banner.jpg` - Banner atau header image
- `icons/` - Folder untuk icon-icon
- `avatars/` - Folder untuk avatar pengguna

## Penggunaan

Untuk menggunakan gambar dalam view blade:

```blade
<img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
```

Untuk menggunakan dalam CSS:

```css
background-image: url('/assets/images/banner.jpg');
```

## Catatan

Pastikan file gambar sudah ada di folder ini sebelum digunakan di view.
Jika gambar tidak ditemukan, script.js akan otomatis menampilkan placeholder SVG.
