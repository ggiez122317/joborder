import html2canvas from 'html2canvas';
import { jsPDF } from 'jspdf';

// Laravel Vite entry point. The PDS interface uses Blade and minimal inline JavaScript.

const ID_EXPORT_DPI = 600;
const CSS_DPI = 96;
const BATCH_EXPORT_SCALE = 3;

const renderIdCard = async (element, scale = ID_EXPORT_DPI / CSS_DPI) => {
    if (document.fonts?.ready) {
        await document.fonts.ready;
    }

    await Promise.all(Array.from(element.querySelectorAll('img')).map((image) => {
        if (image.complete && image.naturalWidth > 0) {
            return Promise.resolve();
        }

        return new Promise((resolve, reject) => {
            image.addEventListener('load', resolve, { once: true });
            image.addEventListener('error', () => reject(new Error('An ID-card image could not be loaded.')), { once: true });
        });
    }));

    return html2canvas(element, {
        backgroundColor: '#ffffff',
        imageTimeout: 30000,
        logging: false,
        scale,
        useCORS: true,
    });
};

const saveCanvas = (canvas, filename) => {
    canvas.toBlob((blob) => {
        if (!blob) {
            window.alert('The HD image could not be created.');
            return;
        }

        const link = document.createElement('a');
        link.download = filename;
        link.href = URL.createObjectURL(blob);
        link.click();
        window.setTimeout(() => URL.revokeObjectURL(link.href), 1000);
    }, 'image/png');
};

const downloadIdCard = async (side) => {
    const front = document.querySelector('.id-container');
    const back = document.querySelector('.id-container-back');
    const buttons = document.querySelectorAll('[data-id-download]');
    const baseName = document.body.dataset.idDownloadName || 'id-card';

    if (!front || !back) {
        return;
    }

    buttons.forEach((button) => button.disabled = true);

    try {
        if (side === 'front') {
            saveCanvas(await renderIdCard(front), `${baseName}-front-hd.png`);
            return;
        }

        if (side === 'back') {
            saveCanvas(await renderIdCard(back), `${baseName}-back-hd.png`);
            return;
        }

        const [frontCanvas, backCanvas] = await Promise.all([
            renderIdCard(front),
            renderIdCard(back),
        ]);
        const gap = 64;
        const combined = document.createElement('canvas');
        combined.width = frontCanvas.width + backCanvas.width + gap;
        combined.height = Math.max(frontCanvas.height, backCanvas.height);

        const context = combined.getContext('2d');
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, combined.width, combined.height);
        context.drawImage(frontCanvas, 0, 0);
        context.drawImage(backCanvas, frontCanvas.width + gap, 0);
        saveCanvas(combined, `${baseName}-both-sides-hd.png`);
    } catch (error) {
        console.error(error);
        window.alert(error.message || 'The HD image could not be created.');
    } finally {
        buttons.forEach((button) => button.disabled = false);
    }
};

const downloadBatchPdf = async () => {
    const side = document.body.dataset.batchSide || 'both';
    const fronts = Array.from(document.querySelectorAll('.print-grid .id-container'));
    const backs = Array.from(document.querySelectorAll('.print-grid .id-container-back'));
    const buttons = document.querySelectorAll('[data-batch-pdf]');
    const status = document.querySelector('[data-batch-pdf-status]');
    const fileName = document.body.dataset.batchPdfName || 'id-cards-bulk.pdf';

    // DOM order already pairs front+back per employee; flatten to render order.
    let queue = [];
    if (side === 'front') {
        queue = fronts;
    } else if (side === 'back') {
        queue = backs;
    } else {
        fronts.forEach((front, index) => {
            queue.push(front);
            if (backs[index]) {
                queue.push(backs[index]);
            }
        });
    }

    if (queue.length === 0) {
        window.alert('No ID cards found on this page.');
        return;
    }

    // A4 sheet (inches) with 10mm margins; cards keep their 3.5 x 5.5in size, 2-up.
    const margin = 0.394;
    const cardW = 3.5;
    const cardH = 5.5;
    const gap = 0.48;
    const xPositions = [margin, margin + cardW + gap];
    const yPosition = 0.5;

    buttons.forEach((button) => button.disabled = true);

    try {
        const pdf = new jsPDF({ unit: 'in', format: 'a4', orientation: 'portrait' });

        for (const [index, element] of queue.entries()) {
            if (status) {
                status.textContent = `Rendering card ${index + 1} of ${queue.length}…`;
            }

            const canvas = await renderIdCard(element, BATCH_EXPORT_SCALE);

            if (index > 0 && index % 2 === 0) {
                pdf.addPage('a4', 'portrait');
            }
            const x = xPositions[index % 2];
            pdf.addImage(canvas.toDataURL('image/png'), 'PNG', x, yPosition, cardW, cardH);
        }

        pdf.save(fileName);
    } catch (error) {
        console.error(error);
        window.alert(error.message || 'The combined PDF could not be created.');
    } finally {
        buttons.forEach((button) => button.disabled = false);
        if (status) {
            status.textContent = '';
        }
    }
};

document.querySelectorAll('[data-batch-pdf]').forEach((button) => {
    button.addEventListener('click', downloadBatchPdf);
});

document.querySelectorAll('[data-id-download-menu]').forEach((menu) => {    menu.querySelector('[data-id-download-toggle]')?.addEventListener('click', (event) => {
        event.stopPropagation();
        menu.classList.toggle('open');
    });

    menu.querySelectorAll('[data-id-download]').forEach((button) => {
        button.addEventListener('click', () => downloadIdCard(button.dataset.idDownload));
    });
});

document.addEventListener('click', () => {
    document.querySelectorAll('[data-id-download-menu].open').forEach((menu) => menu.classList.remove('open'));
});

(() => {
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const close = toast.querySelector('[data-toast-close]');
        const dismiss = () => {
            toast.classList.add('toast-hide');
            window.setTimeout(() => toast.remove(), 220);
        };

        close?.addEventListener('click', dismiss);
        window.setTimeout(dismiss, 3600);
    });

    const shell = document.querySelector('.app-shell');
    const desktopToggle = document.getElementById('desktopSidebarToggle');
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('appSidebar');
    const sidebarBackdrop = document.getElementById('appSidebarBackdrop');

    if (!shell) {
        return;
    }

    const storageKey = 'pds-sidebar-collapsed';

    if (window.matchMedia('(min-width: 768px)').matches && localStorage.getItem(storageKey) === '1') {
        shell.classList.add('sidebar-collapsed');
    }

    desktopToggle?.addEventListener('click', () => {
        shell.classList.toggle('sidebar-collapsed');
        localStorage.setItem(storageKey, shell.classList.contains('sidebar-collapsed') ? '1' : '0');
    });

    mobileToggle?.addEventListener('click', () => {
        shell.classList.toggle('sidebar-open');
    });

    sidebarBackdrop?.addEventListener('click', () => {
        shell.classList.remove('sidebar-open');
    });

    document.addEventListener('click', (event) => {
        if (!shell.classList.contains('sidebar-open')) {
            return;
        }

        const target = event.target;

        if (sidebar?.contains(target) || mobileToggle?.contains(target)) {
            return;
        }

        shell.classList.remove('sidebar-open');
    });
})();
