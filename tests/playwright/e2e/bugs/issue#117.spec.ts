import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: PPID tidak bisa diakses setelah update #117', () => {
  test('fix: PPID tidak bisa diakses setelah update', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/modul-ppid/issues/117',
    },
  }, async ({ page }) => {
    await page.goto('database/migrasi_cri');
    await page.getByRole('button', { name: ' Migrasi Database' }).click();
    await page.getByRole('link', { name: 'Semua Migrasi' }).click();
    await page.getByRole('button', { name: 'Sudah' }).click();

    // Tunggu proses migrasi selesai
    // Bisa menunggu redirect atau pesan sukses
    await page.waitForTimeout(5000); // Berikan waktu untuk proses migrasi
    
    // Tunggu sampai tidak ada loading atau proses yang berjalan
    await page.waitForLoadState('networkidle');

    await page.getByRole('button', { name: 'OK' }).click();
    
    await page.goto('ppid/daftar-dokumen');
    await page.waitForLoadState('networkidle');

    await expect(page.getByRole('heading', { name: 'Daftar Dokumen' })).toBeVisible();
  });
});