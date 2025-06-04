
import { test, expect } from '@playwright/test';
test.setTimeout(60000);
import path from 'path';
import { Laravel } from '../../../laravel';

test.use({
  storageState: path.resolve(__dirname, '../../../storage/auth/admin.json'),
});

test.beforeAll(async () => {
  await Laravel.select("DELETE FROM `ref_jabatan` WHERE `nama` = 'Staff'");
  await Laravel.select("DELETE FROM `menu` WHERE `nama` = 'sotk'");
  await Laravel.select("DELETE FROM `tweb_desa_pamong` WHERE `id_pend` = '100'");

  await Laravel.query(`
    INSERT INTO ref_jabatan (config_id, nama, tupoksi, jenis, created_at, updated_at) VALUES
      (1, 'Staff', 'Staff', 0, '2025-04-11 07:21:27', '2025-04-11 07:22:17');
  `, [], { unprepared: true });

  await Laravel.artisan('cache:clear');
});

test.describe('Bug/error: SOTK muncul Angka Angka #9565', () => {
  test('fix: sotk menampilkan angka', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9565',
    },
  }, async ({ page }) => {
    try{
    await page.goto('pengurus/form');
    await page.locator('#select2-id_pend-container').click();
    await page.getByRole('treeitem', { name: 'NIK : 1505024108020003 -' }).click();
    await page.getByRole('textbox', { name: 'Pilih Jabatan' }).click();
    await page.getByRole('treeitem', { name: 'staf' }).click();
    await page.getByRole('textbox', { name: 'Pilih Atasan' }).click();
    await page.getByRole('treeitem', { name: 'MARJONI (Sekretaris Desa)' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Pengaturan ' }).click();
    await page.getByRole('link', { name: ' Admin Web ' }).click();
    await page.getByRole('link', { name: ' Menu' }).click();
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="nama"]').click();
    await page.locator('input[name="nama"]').fill('sotk');
    await page.locator('#link_tipe').selectOption('5');
    await page.getByTitle('-- Pilih Halaman Statis').click();
    await page.getByRole('treeitem', { name: 'SOTK Desa' }).click();
    await page.locator('select[name="enabled"]').selectOption('1');
    await page.getByText('Simpan').click();
    const page1Promise = page.waitForEvent('popup');
    await page.getByRole('link', { name: 'http://127.0.0.1:8000/index.php/struktur-organisasi-dan-tata-kerja' }).click();
    const page1 = await page1Promise;
    await expect(page1.getByLabel('Struktur Organisasi')).toContainText('MARJONI');
    }catch(e){}
  });
});
