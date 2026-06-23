<footer class="dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 transition-all duration-300" id="mainFooter">
    <!-- Mobile View -->
    <div class="block md:hidden px-4 py-4">
        <div class="flex justify-between items-start">
            <div class="bg-black rounded-md p-1.5">
                <img src="{{ asset('assets/logoFooter.webp') }}" alt="POLMIND Logo" class="h-7 w-auto">
            </div>
            <div class="flex gap-4 text-xs">
                <a href="https://www.polmind.ac.id/beranda" target="_blank"
                   class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <span data-translate="about" data-translate-page="footer">About</span>
                </a>
                <a href="{{ route('help', ['locale' => app()->getLocale()]) }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <span data-translate="help" data-translate-page="footer">Help</span>
                </a>
                <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <span data-translate="get_app" data-translate-page="footer">Get App</span>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap justify-between items-center gap-2 mt-3">
            <div class="flex flex-wrap gap-3 text-xs">
                <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}?text=Halo%20saya%20butuh%20informasi%20mengenai%20POLMIND"
                   target="_blank" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-green-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span data-translate="wa" data-translate-page="footer">WA</span>
                </a>
                <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}?subject=Informasi%20POLMIND"
                   target="_blank" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-blue-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span data-translate="email" data-translate-page="footer">Email</span>
                </a>
                <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}"
                   onclick="openMapModal(event)" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-red-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span data-translate="maps" data-translate-page="footer">Maps</span>
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ env('SOCIAL_WEBSITE', 'https://polmind.ac.id') }}" target="_blank"
                   class="w-6 h-6 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                    </svg>
                </a>
                <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank"
                   class="w-6 h-6 bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-8.662a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/>
                    </svg>
                </a>
                <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank"
                   class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                    </svg>
                </a>
                <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank"
                   class="w-6 h-6 bg-black rounded-full flex items-center justify-center transition-transform hover:scale-105">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Map for Mobile -->
        <div id="mapModal"
          class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

          <div class="relative w-[90%] max-w-3xl bg-white dark:bg-gray-900 rounded-xl shadow-xl p-4">

              <!-- Close button -->
              <button 
                  onclick="closeMapModal()"
                  class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
                  ✕
              </button>


              <!-- MAP CONTENT -->
              <div class="mt-4">
                  <div class="map-container map-wrapper w-full h-[400px] rounded-lg overflow-hidden">
                  </div>
              </div>


              <div class="text-center border-t border-gray-200 dark:border-gray-800 mt-3 pt-2">
                  <p class="text-[11px] text-gray-500 dark:text-gray-400">
                      &copy; {{ date('Y') }} {{ config('app.name', 'POLMIND') }}
                      <span data-translate="all_rights" data-translate-page="footer">
                          All rights reserved.
                      </span>
                  </p>
              </div>

          </div>
        </div>
    </div>

    <!-- Desktop & Tablet View -->
    <div class="hidden md:block px-6 py-3">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="bg-black rounded-md p-1.5">
                    <img src="{{ asset('assets/logoFooter.webp') }}" alt="POLMIND Logo" class="h-7 w-auto">
                </div>
                <div class="flex gap-4 text-sm">
                    <a href="https://www.polmind.ac.id/beranda" target="_blank"
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        <span data-translate="about" data-translate-page="footer">About</span>
                    </a>
                    <a href="{{ route('help', ['locale' => app()->getLocale()]) }}"
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        <span data-translate="help" data-translate-page="footer">Help</span>
                    </a>
                    <a href="{{ route('get-app', ['locale' => app()->getLocale()]) }}"
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        <span data-translate="get_app" data-translate-page="footer">Get App</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-5 text-sm">
                <div class="flex gap-3">
                    <a href="https://wa.me/{{ env('CONTACT_PHONE', '6282113296897') }}" target="_blank"
                       class="text-gray-600 dark:text-gray-300 hover:text-green-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span data-translate="wa" data-translate-page="footer">WA</span>
                    </a>
                    <a href="mailto:{{ env('CONTACT_EMAIL', 'info@polmind.ac.id') }}" target="_blank"
                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span data-translate="email" data-translate-page="footer">Email</span>
                    </a>
                    <a href="{{ env('CONTACT_MAPS', 'https://maps.app.goo.gl/UgUBmN7joH9fA2Jw5') }}" 
                      onclick="openMapModal(event)"
                      class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-red-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span data-translate="maps" data-translate-page="footer">Maps</span>
                    </a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ env('SOCIAL_WEBSITE', 'https://polmind.ac.id') }}" target="_blank"
                       class="w-7 h-7 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_INSTAGRAM', 'https://instagram.com') }}" target="_blank"
                       class="w-7 h-7 bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-8.662a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_YOUTUBE', 'https://youtube.com') }}" target="_blank"
                       class="w-7 h-7 bg-red-600 rounded-full flex items-center justify-center transition-transform hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15l6-3-6-3v6z"/>
                        </svg>
                    </a>
                    <a href="{{ env('SOCIAL_TIKTOK', 'https://tiktok.com') }}" target="_blank"
                       class="w-7 h-7 bg-black rounded-full flex items-center justify-center transition-transform hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Map for Desktop -->
        <div id="mapModal"
          class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">

          <div class="relative w-[90%] max-w-3xl bg-white dark:bg-gray-900 rounded-xl shadow-xl p-4">

              <!-- Close button -->
              <button 
                  onclick="closeMapModal()"
                  class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">
                  ✕
              </button>


              <!-- MAP CONTENT -->
              <div class="mt-4">
                  <div class="map-container map-wrapper w-full h-[400px] rounded-lg overflow-hidden">
                  </div>
              </div>


              <div class="text-center border-t border-gray-200 dark:border-gray-800 mt-3 pt-2">
                  <p class="text-[11px] text-gray-500 dark:text-gray-400">
                      &copy; {{ date('Y') }} {{ config('app.name', 'POLMIND') }}
                      <span data-translate="all_rights" data-translate-page="footer">
                          All rights reserved.
                      </span>
                  </p>
              </div>

          </div>
      </div>
    </div>

    
<style>
.footer-map {
  margin-top: 1rem;
}

.footer-map h5 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: #1e293b;
}

.map-wrapper {
  width: 100%;
  max-width: 450px;
  height: 180px;
  margin: 0.5rem auto 0;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  position: relative;
  background: #e2e8f0;
}

.map-address {
  font-size: 11px;
  margin-top: 6px;
  color: #64748b;
}

/* Control styling */
.map-controls {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 10;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  overflow: hidden;
}

.map-controls select {
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 500;
  border: none;
  background: white;
  cursor: pointer;
  font-family: inherit;
  outline: none;
}

.map-controls select:hover {
  background: #f1f5f9;
}

/* 3D toggle button */
.btn-3d-toggle {
  position: absolute;
  bottom: 12px;
  right: 12px;
  z-index: 10;
  background: rgba(0,0,0,0.75);
  backdrop-filter: blur(4px);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 11px;
  font-weight: 500;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  cursor: pointer;
  font-family: inherit;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
}

.btn-3d-toggle:hover {
  background: rgba(0,0,0,0.9);
}

/* Responsive */
@media (max-width: 768px) {
  .map-wrapper {
    height: 150px;
  }
}

@media (max-width: 480px) {
  .map-wrapper {
    height: 130px;
  }
}
</style>

<script>
(function() {
  
  const POLMIND_COORDS = [107.082872, -6.288922]; 
  const ZOOM_LEVEL = 17.5;
  const PITCH_3D = 62;
  const PITCH_2D = 0;
  const BEARING = -25;

  let maps = [];
  let is3DMode = true;

  const TILE_STYLES = {
    'default': 'https://tiles.openfreemap.org/styles/liberty',
    'bright': 'https://tiles.stadiamaps.com/tiles/osm_bright/{z}/{x}/{y}.png',
    'outdoor': 'https://tiles.stadiamaps.com/tiles/outdoors/{z}/{x}/{y}.png'
  };

  function initMaps() {
    const containers = document.querySelectorAll('.map-container');
    containers.forEach((container) => {
      if (container.dataset.initialized) return;
      container.dataset.initialized = 'true';

      const map = new maplibregl.Map({
        container: container,
        style: TILE_STYLES['default'],
        center: POLMIND_COORDS,
        zoom: ZOOM_LEVEL,
        pitch: PITCH_3D,
        bearing: BEARING,
        antialias: true,
        attributionControl: true
      });

      map.addControl(new maplibregl.NavigationControl(), 'top-right');
      map.addControl(new maplibregl.ScaleControl(), 'bottom-left');

      map.on('load', () => {
        const markerElement = document.createElement('div');
        markerElement.innerHTML = '<i class="fas fa-map-marker-alt" style="font-size: 32px; color: #e11d48; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>';
        markerElement.style.cursor = 'pointer';
        
        new maplibregl.Marker({ element: markerElement })
          .setLngLat(POLMIND_COORDS)
          .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML(`
            <div style="font-family: sans-serif; padding: 4px;">
              <strong style="color: #1e293b;">🏫 Politeknik Mitra Industri</strong><br>
              <span style="font-size: 12px;">MM2100 Industrial Town, Cikarang</span><br>
              <span style="font-size: 11px; color: #64748b;">Kampus Industri Terintegrasi</span>
            </div>
          `))
          .addTo(map);

        add3DBuildingsLayer(map);
      });

      // Add 3D toggle button specifically inside this container
      const toggleBtn = document.createElement('button');
      toggleBtn.className = 'btn-3d-toggle';
      toggleBtn.innerHTML = '<i class="fas fa-cube"></i> 3D Mode ON';
      toggleBtn.addEventListener('click', () => {
        is3DMode = !is3DMode;
        maps.forEach(m => {
          m.easeTo({
            pitch: is3DMode ? PITCH_3D : PITCH_2D,
            bearing: is3DMode ? BEARING : 0,
            duration: 800,
            zoom: is3DMode ? ZOOM_LEVEL : ZOOM_LEVEL - 0.5
          });
        });
        document.querySelectorAll('.btn-3d-toggle').forEach(btn => {
          btn.innerHTML = is3DMode ? '<i class="fas fa-cube"></i> 3D Mode ON' : '<i class="fas fa-map"></i> 3D Mode OFF';
        });
      });
      container.appendChild(toggleBtn);

      maps.push(map);
    });
  }

  function add3DBuildingsLayer(map) {
    if (!map.isStyleLoaded()) {
      map.once('styledata', () => add3DBuildingsLayer(map));
      return;
    }

    if (!map.getSource('openmaptiles')) {
      map.addSource('openmaptiles', {
        type: 'vector',
        url: 'https://tiles.openfreemap.org/data/v3.json'
      });
    }

    if (map.getLayer('3d-buildings')) {
      map.removeLayer('3d-buildings');
    }

    map.addLayer({
      'id': '3d-buildings',
      'type': 'fill-extrusion',
      'source': 'openmaptiles',
      'source-layer': 'building',
      'minzoom': 14,
      'paint': {
        'fill-extrusion-color': [
          'interpolate',
          ['linear'],
          ['get', 'render_height'],
          0, '#d4c9b8',
          5, '#c4b8a8',
          10, '#b4a898',
          20, '#a49888',
          30, '#948878'
        ],
        'fill-extrusion-height': [
          'interpolate',
          ['linear'],
          ['zoom'],
          14, 0,
          15, ['get', 'render_height'],
          18, ['get', 'render_height']
        ],
        'fill-extrusion-base': [
          'case',
          ['has', 'render_min_height'],
          ['get', 'render_min_height'],
          0
        ],
        'fill-extrusion-opacity': 0.9
      }
    });
  }

  // Run
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => { initMaps(); });
  } else {
    initMaps();
  }

  // Resize handler
  let resizeTimer;
  window.addEventListener('resize', () => {
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { maps.forEach(m => m.resize()); }, 200);
  });
})();

function openMapModal(e){
    e.preventDefault();

    const modal = document.getElementById('mapModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeMapModal(){

    const modal = document.getElementById('mapModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

}


// klik area luar modal untuk close
document.getElementById('mapModal').addEventListener('click', function(e){

    if(e.target === this){
        closeMapModal();
    }

});

</script>
</footer>