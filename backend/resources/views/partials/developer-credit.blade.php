@if (($variant ?? 'footer') === 'panel')
    <section class="developer-panel reveal-on-scroll" aria-label="Developer attribution">
        <div class="developer-monogram">CM</div>
        <div>
            <span class="developer-kicker">Built with care</span>
            <strong>Developed by Curtis Muthomi</strong>
            <span>Junior Software Developer · <a href="tel:+254727572375">+254 727 572 375</a></span>
        </div>
    </section>
@else
    <footer class="site-footer">
        <span class="footer-brand">Stockroom</span>
        <span>Developed by <strong>Curtis Muthomi</strong></span>
        <span>Junior Software Developer · <a href="tel:+254727572375">+254 727 572 375</a></span>
    </footer>
@endif
