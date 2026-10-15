document.addEventListener('DOMContentLoaded', () => {
  const menuButton = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.main-nav');
  const closeMenu = () => { if (!menu || !menuButton) return; menu.classList.remove('open'); menuButton.setAttribute('aria-expanded', 'false'); document.body.classList.remove('menu-open'); };
  if (menuButton && menu) {
    menuButton.addEventListener('click', () => { const open = menu.classList.toggle('open'); menuButton.setAttribute('aria-expanded', String(open)); document.body.classList.toggle('menu-open', open); });
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('click', (event) => { if (menu.classList.contains('open') && !menu.contains(event.target) && !menuButton.contains(event.target)) closeMenu(); });
  }
  const sidebarButton = document.querySelector('.admin-menu-toggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebarButton && sidebar) sidebarButton.addEventListener('click', () => { const open = sidebar.classList.toggle('open'); sidebarButton.setAttribute('aria-expanded', String(open)); });
  document.addEventListener('keydown', (event) => { if (event.key === 'Escape') { closeMenu(); const dialog = document.querySelector('.lightbox[open]'); if (dialog) dialog.close(); } });
  document.querySelectorAll('[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => { if (!window.confirm(form.dataset.confirm)) event.preventDefault(); }));
  const dialog = document.querySelector('.lightbox');
  if (dialog) {
    const image = dialog.querySelector('img'); const caption = dialog.querySelector('p');
    document.querySelectorAll('[data-lightbox]').forEach((trigger) => trigger.addEventListener('click', () => { image.src = trigger.dataset.lightbox; image.alt = trigger.dataset.caption || ''; caption.textContent = trigger.dataset.caption || ''; dialog.showModal(); }));
    dialog.querySelector('.lightbox-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
  }
});
