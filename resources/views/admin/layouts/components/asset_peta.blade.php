@push('css')
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/leaflet-geoman.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/L.Control.Locate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/MarkerCluster.Default.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/leaflet-measure-path.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/mapbox/css/mapbox-gl.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/L.Control.Shapefile.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/leaflet.groupedlayercontrol.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peta.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/leaflet/css/leaflet.fullscreen.css') }}" />
@endpush

@push('scripts')
    <!-- OpenStreetMap Js-->
    <script src="{{ asset('lib/leaflet/js/leaflet.js') }}"></script>
    <script src="{{ asset('js/turf.min.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet-geoman.min.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/togeojson.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/togpx.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet-providers.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/L.Control.Locate.min.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.markercluster.js') }}"></script>
    <script src="{{ asset('js/peta.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet-measure-path.js') }}"></script>
    <script src="{{ asset('js/apbdes_manual.js') }}"></script>
    <script src="{{ asset('lib/mapbox/js/mapbox-gl.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet-mapbox-gl.js') }}"></script>
    <script src="{{ asset('js/shp.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.shpfile.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.groupedlayercontrol.min.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.browser.print.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.browser.print.utils.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.browser.print.sizes.js') }}"></script>
    <script src="{{ asset('js/dom-to-image.min.js') }}"></script>
    <script src="{{ asset('lib/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('lib/leaflet/js/leaflet.fullscreen.min.js') }}"></script>
    <script>
        // pengaturan peta
        var MAPBOX_KEY = '{{ setting('mapbox_key') }}';
        var JENIS_PETA = '{{ setting('jenis_peta') }}';
        var TAMPIL_LUAS = "{{ setting('tampil_luas_peta') }}";
        var pengaturan_peta = {
            maxZoom: '{{ setting('max_zoom_peta') }}',
            minZoom: '{{ setting('min_zoom_peta') }}',
            fullscreenControl: {
                position: 'topright' // Menentukan posisi tombol fullscreen
            }
        };
    </script>
@endpush
