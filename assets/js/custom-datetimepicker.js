/**
	Javascript untuk penggunaan dan penyesuaian datepicker dan datetimepicker
*/

$(document).ready(function () {
	/** Mulai debugging */
	console.log('Script datepicker dan datetimepicker dimuat.');

	// Cek pustaka
	const isMomentAvailable = typeof moment !== 'undefined';
	const isDatepickerAvailable = typeof $.fn.datepicker !== 'undefined';
	const isDatetimepickerAvailable = typeof $.fn.datetimepicker !== 'undefined';

	// cetak console.log
	console.log('Moment.js tersedia:', isMomentAvailable);
	console.log('Datepicker tersedia:', isDatepickerAvailable);
	console.log('Datetimepicker tersedia:', isDatetimepickerAvailable);

	if (!isMomentAvailable) {
		console.error('Moment.js tidak ditemukan. Pastikan file dimuat.');
	}

	if (!isDatepickerAvailable) {
		console.error('Pustaka datepicker tidak ditemukan. Pastikan file dimuat.');
	}

	if (!isDatetimepickerAvailable) {
		console.error('Pustaka datetimepicker tidak ditemukan. Pastikan file dimuat.');
	}

	/** Akhir debugging */

	//Fortmat Tanggal dan Jam
	if (isDatepickerAvailable) {
		$('.datepicker:visible').datepicker({
			weekStart: 1,
			language: 'id',
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	}

	if (isMomentAvailable && isDatetimepickerAvailable) {
		var defaultTglMulai = ($('#tgl_mulai').val() != '') ? moment($('#tgl_mulai').val(), 'DD-MM-YYYY') : moment();

		$('#tgl_mulai').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY',
			useCurrent: false,
			date: defaultTglMulai
		});

		var defaultTglAkhir = ($('#tgl_akhir').val() != '') ? moment($('#tgl_akhir').val(), 'DD-MM-YYYY') : moment(new Date()).add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku'));
		$('#tgl_akhir').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY',
			useCurrent: false,
			minDate: defaultTglMulai,
			date: defaultTglAkhir
		});

		$('#tgl_mulai').datetimepicker().on('dp.change', function (e) {
			var tglMulai = moment(new Date(e.date));
			var defaultTglAkhir = ($('#tgl_akhir').val() != '') ? moment($('#tgl_akhir').val(), 'DD-MM-YYYY') : moment();

			$('#tgl_akhir').data('DateTimePicker').minDate(tglMulai);
			if (tglMulai.isAfter(defaultTglAkhir)) {
				$('#tgl_akhir').data('DateTimePicker').date(tglMulai.add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku')));
			}
			$(this).data("DateTimePicker").hide();
		});

		$('.tgl_minimal').datetimepicker().on('dp.change', function (e) {
			var tgl_lebih_besar = $(this).data('tgl-lebih-besar');
			$(tgl_lebih_besar).data('DateTimePicker').minDate(moment(new Date(e.date)));
			$(this).data("DateTimePicker").hide();
		});

		$('#tgljam_mulai').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY HH:mm',
			useCurrent: false,
			date: moment(new Date()),
			sideBySide: true
		});

		$('#tgljam_akhir').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY HH:mm',
			useCurrent: false,
			minDate: moment(new Date()).add(-1, 'day'), // Todo: mengapa harus dikurangi -- bug?
			date: moment(new Date()).add(1, 'day'),
			sideBySide: true
		});

		$('#tgljam_mulai').datetimepicker().on('dp.change', function (e) {
			$('#tgljam_akhir').data('DateTimePicker').minDate(moment(new Date(e.date)));
			var tglAkhir = moment(new Date(e.date));
			tglAkhir.add(1, 'day');
			$('#tgljam_akhir').data('DateTimePicker').date(tglAkhir);
		});

		$('.tgl_jam').datetimepicker({
			format: 'DD-MM-YYYY HH:mm:ss',
			locale: 'id'
		});

		$('.tgl').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				useCurrent: false,
				locale: 'id'
			});
		$('.tgl_indo').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('#tgl_1').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('.tgl_1').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('#tgl_2').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('#tgl_3').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('#tgl_4').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('#tgl_5').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id'
			});
		$('.tgl_sekarang').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id',
				maxDate: moment(new Date())
			});
		$('.jam').datetimepicker(
			{
				format: 'HH:mm:ss',
				locale: 'id'
			});
		$('#jam_1').datetimepicker(
			{
				format: 'HH:mm:ss',
				locale: 'id'
			});
		$('#jam_2').datetimepicker(
			{
				format: 'HH:mm:ss',
				locale: 'id'
			});
		$('#jam_3').datetimepicker(
			{
				format: 'HH:mm:ss',
				locale: 'id'
			});
	
		$('#jammenit_1').datetimepicker(
			{
				format: 'HH:mm',
				locale: 'id'
			});
		$('#jammenit_2').datetimepicker(
			{
				format: 'HH:mm',
				locale: 'id'
			});
	
		$('#jammenit_3').datetimepicker(
			{
				format: 'HH:mm',
				locale: 'id'
			});
		$('#tanggal_cetak_ktp').datetimepicker(
			{
				format: 'DD-MM-YYYY',
				locale: 'id',
				maxDate: new (Date)
			});
		$('#surat_tgl_mulai').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY',
			useCurrent: false,
			defaultDate: moment(new Date())
		});
		$('#surat_tgl_akhir').datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY',
			useCurrent: false,
			minDate: moment(new Date()).add($('#surat_tgl_akhir').data('masa-berlaku'), $('#surat_tgl_akhir').data('satuan-masa-berlaku')),
			defaultDate: moment(new Date()).add($('#surat_tgl_akhir').data('masa-berlaku'), $('#surat_tgl_akhir').data('satuan-masa-berlaku'))
		});

		$('#surat_tgl_mulai').datetimepicker().on('dp.change', function (e) {
			var startDate = moment(e.date);
			var endDate = startDate.clone().add($('#surat_tgl_mulai').data('masa-berlaku'), $('#surat_tgl_mulai').data('satuan-masa-berlaku'));
			$('#surat_tgl_akhir').datetimepicker('minDate', endDate);
			$('#surat_tgl_akhir').datetimepicker('date', endDate);
		});

	} // end moment & datetimepicker check
	
});
