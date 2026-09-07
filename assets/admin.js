(function () {
    'use strict';

    function uuid() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }

        return 'id-' + Math.random().toString(36).slice(2) + Date.now().toString(36);
    }

    function escapeAttr(value) {
        var div = document.createElement('div');
        div.textContent = value || '';

        return div.innerHTML.replace(/"/g, '&quot;');
    }

    function findIcon(icons, value) {
        for (var i = 0; i < icons.length; i++) {
            if (icons[i].value === value) {
                return icons[i];
            }
        }

        return null;
    }

    function buildIconField(link, i18n) {
        var icons = i18n.icons || [];
        var selected = link.icon || '';
        var options = '<option value="">' + escapeAttr(i18n.iconNone) + '</option>';

        icons.forEach(function (icon) {
            options += '<option value="' + escapeAttr(icon.value) + '"' + (icon.value === selected ? ' selected' : '') + '>' + escapeAttr(icon.label) + '</option>';
        });

        var previewIcon = findIcon(icons, selected);
        var previewSvg = previewIcon ? previewIcon.svg : '';

        return (
            '<span class="pp-links-hub-row__icon">' +
            '<span class="pp-links-hub-row__icon-preview">' + previewSvg + '</span>' +
            '<select class="pp-links-hub-row__icon-select" aria-label="' + escapeAttr(i18n.icon) + '">' + options + '</select>' +
            '</span>'
        );
    }

    function createRow(link, i18n) {
        link = link || {};

        var row = document.createElement('div');
        row.className = 'pp-links-hub-row';
        row.draggable = true;
        row.dataset.id = link.id || uuid();
        row.dataset.type = link.type || 'link';

        row.innerHTML =
            '<div class="pp-links-hub-row__handle" title="' + escapeAttr(i18n.dragHandle) + '">☰</div>' +
            '<input type="text" class="pp-links-hub-row__label" placeholder="' + escapeAttr(i18n.label) + '" value="' + escapeAttr(link.label) + '">' +
            '<input type="url" class="pp-links-hub-row__url" placeholder="' + escapeAttr(i18n.url) + '" value="' + escapeAttr(link.url) + '">' +
            buildIconField(link, i18n) +
            '<label class="pp-links-hub-row__enabled"><input type="checkbox"' + (link.enabled === false ? '' : ' checked') + '> ' + escapeAttr(i18n.enabled) + '</label>' +
            '<label class="pp-links-hub-row__starts">' + escapeAttr(i18n.startsAt) + '<input type="datetime-local" value="' + escapeAttr(link.starts_at) + '"></label>' +
            '<label class="pp-links-hub-row__ends">' + escapeAttr(i18n.endsAt) + '<input type="datetime-local" value="' + escapeAttr(link.ends_at) + '"></label>' +
            '<button type="button" class="button-link pp-links-hub-row__remove" aria-label="' + escapeAttr(i18n.remove) + '">✕</button>';

        var iconSelect = row.querySelector('.pp-links-hub-row__icon-select');
        var iconPreview = row.querySelector('.pp-links-hub-row__icon-preview');

        iconSelect.addEventListener('change', function () {
            var icon = findIcon(i18n.icons || [], iconSelect.value);
            iconPreview.innerHTML = icon ? icon.svg : '';
        });

        return row;
    }

    function serializeRows(container) {
        return Array.prototype.map.call(container.querySelectorAll('.pp-links-hub-row'), function (row) {
            return {
                id: row.dataset.id,
                type: row.dataset.type,
                label: row.querySelector('.pp-links-hub-row__label').value,
                url: row.querySelector('.pp-links-hub-row__url').value,
                icon: row.querySelector('.pp-links-hub-row__icon-select').value,
                enabled: row.querySelector('.pp-links-hub-row__enabled input').checked,
                starts_at: row.querySelector('.pp-links-hub-row__starts input').value,
                ends_at: row.querySelector('.pp-links-hub-row__ends input').value,
            };
        });
    }

    function getRowAfter(container, y) {
        var rows = Array.prototype.filter.call(
            container.querySelectorAll('.pp-links-hub-row'),
            function (row) {
                return ! row.classList.contains('is-dragging');
            }
        );

        var closestOffset = Number.NEGATIVE_INFINITY;
        var closestRow = null;

        rows.forEach(function (row) {
            var box = row.getBoundingClientRect();
            var offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closestOffset) {
                closestOffset = offset;
                closestRow = row;
            }
        });

        return closestRow;
    }

    function initBuilder(builder, i18n) {
        var rowsContainer = builder.querySelector('.pp-links-hub-builder__rows');
        var jsonField = document.getElementById('pp-links-hub-json');
        var initial = [];

        try {
            initial = JSON.parse(builder.dataset.initialLinks || '[]');
        } catch (error) {
            initial = [];
        }

        initial.forEach(function (link) {
            rowsContainer.appendChild(createRow(link, i18n));
        });

        Array.prototype.forEach.call(builder.querySelectorAll('.pp-links-hub-add-link'), function (btn) {
            btn.addEventListener('click', function () {
                rowsContainer.appendChild(createRow({ type: btn.dataset.type, enabled: true }, i18n));
            });
        });

        rowsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('pp-links-hub-row__remove')) {
                event.target.closest('.pp-links-hub-row').remove();
            }
        });

        var draggedRow = null;

        rowsContainer.addEventListener('dragstart', function (event) {
            var row = event.target.closest('.pp-links-hub-row');

            if (! row) {
                return;
            }

            draggedRow = row;
            row.classList.add('is-dragging');
        });

        rowsContainer.addEventListener('dragend', function (event) {
            var row = event.target.closest('.pp-links-hub-row');

            if (row) {
                row.classList.remove('is-dragging');
            }

            draggedRow = null;
        });

        rowsContainer.addEventListener('dragover', function (event) {
            event.preventDefault();

            if (! draggedRow) {
                return;
            }

            var afterRow = getRowAfter(rowsContainer, event.clientY);

            if (afterRow === null) {
                rowsContainer.appendChild(draggedRow);
            } else {
                rowsContainer.insertBefore(draggedRow, afterRow);
            }
        });

        var form = builder.closest('form');

        if (form && jsonField) {
            form.addEventListener('submit', function () {
                jsonField.value = JSON.stringify(serializeRows(rowsContainer));
            });
        }
    }

    function initBackgroundPicker(i18n) {
        var selectBtn = document.querySelector('.pp-links-hub-background-select');
        var removeBtn = document.querySelector('.pp-links-hub-background-remove');
        var hiddenInput = document.getElementById('pp-links-hub-background-id');
        var preview = document.querySelector('.pp-links-hub-background-preview');

        if (! selectBtn || ! hiddenInput || ! preview || ! window.wp || ! window.wp.media) {
            return;
        }

        var frame = null;

        selectBtn.addEventListener('click', function (event) {
            event.preventDefault();

            if (! frame) {
                frame = window.wp.media({
                    title: i18n.chooseBackgroundTitle,
                    button: { text: i18n.chooseBackgroundButton },
                    multiple: false,
                    library: { type: 'image' },
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();

                    hiddenInput.value = attachment.id;
                    preview.style.backgroundImage = 'url(' + attachment.url + ')';

                    var placeholder = preview.querySelector('span');
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }

                    if (removeBtn) {
                        removeBtn.style.display = '';
                    }
                });
            }

            frame.open();
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', function (event) {
                event.preventDefault();

                hiddenInput.value = '';
                preview.style.backgroundImage = '';

                var placeholder = preview.querySelector('span');
                if (placeholder) {
                    placeholder.style.display = '';
                }

                removeBtn.style.display = 'none';
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var builder = document.querySelector('.pp-links-hub-builder');
        var i18n = window.ppLinksHubI18n || {};

        if (builder) {
            initBuilder(builder, i18n);
        }

        initBackgroundPicker(i18n);

        if (window.jQuery && window.jQuery.fn.wpColorPicker) {
            window.jQuery('.pp-links-hub-color-picker').wpColorPicker();
        }
    });
})();
