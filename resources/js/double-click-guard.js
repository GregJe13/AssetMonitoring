const RESTORE_TIMEOUT_MS = 4000;
const elementState = new WeakMap();
const elementTimers = new WeakMap();
const formState = new WeakMap();
const trackedElements = new Set();
const trackedForms = new Set();

const BUTTON_GUARD_SELECTOR = 'button[type="button"][data-prevent-double-click]';
const LINK_GUARD_SELECTOR = 'a[href][data-prevent-double-click-link]';

function isHTMLElement(value) {
    return value instanceof HTMLElement;
}

function hasOptOut(element) {
    return isHTMLElement(element) && Boolean(element.closest('[data-allow-multi-click]'));
}

function getGuardMode(element) {
    return isHTMLElement(element) ? element.dataset.guardMode || 'normal' : 'normal';
}

function getLoadingText(element, fallbackText) {
    return element?.dataset?.loadingText?.trim() || fallbackText;
}

function getEffectiveFormMethod(form) {
    const spoofedMethod = form.querySelector('input[name="_method"]')?.value;
    const method = (spoofedMethod || form.getAttribute('method') || 'GET').toUpperCase();

    return method;
}

function shouldGuardForm(form) {
    if (!(form instanceof HTMLFormElement) || hasOptOut(form)) {
        return false;
    }

    return getEffectiveFormMethod(form) !== 'GET';
}

function shouldIgnoreLink(link) {
    const href = (link.getAttribute('href') || '').trim().toLowerCase();

    if (!href || href === '#' || href.startsWith('#')) {
        return true;
    }

    return href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:');
}

function isButtonLikeLink(link) {
    if (link.matches(LINK_GUARD_SELECTOR)) {
        return true;
    }

    if (link.hasAttribute('download')) {
        return true;
    }

    if ((link.getAttribute('target') || '').toLowerCase() === '_blank') {
        return true;
    }

    if (link.dataset.guardMode) {
        return true;
    }

    const classes = Array.from(link.classList);
    const hasBtnClass = classes.some((className) => className === 'btn' || className.startsWith('btn-'));
    const hasRoundedClass = classes.some((className) => className.startsWith('rounded'));
    const hasBgClass = classes.some((className) => className.startsWith('bg-'));
    const hasShadowClass = classes.some((className) => className.startsWith('shadow'));
    const hasRingClass = classes.some((className) => className.startsWith('ring-'));

    return hasBtnClass || (hasRoundedClass && (hasBgClass || hasShadowClass || hasRingClass));
}

function shouldGuardLink(link) {
    if (!(link instanceof HTMLAnchorElement) || hasOptOut(link) || shouldIgnoreLink(link)) {
        return false;
    }

    return isButtonLikeLink(link);
}

function shouldGuardButton(button) {
    if (!(button instanceof HTMLButtonElement) || hasOptOut(button)) {
        return false;
    }

    return button.matches(BUTTON_GUARD_SELECTOR) || Boolean(button.dataset.guardMode);
}

function isElementLocked(element) {
    return elementState.has(element);
}

function isFormLocked(form) {
    return formState.has(form);
}

function clearRestoreTimer(element) {
    const timerId = elementTimers.get(element);

    if (timerId) {
        window.clearTimeout(timerId);
        elementTimers.delete(element);
    }
}

function buildLoadingMarkup(label) {
    return [
        '<span class="double-click-loading-label">',
        '<span class="double-click-spinner" aria-hidden="true"></span>',
        `<span>${label}</span>`,
        '</span>',
    ].join('');
}

function setElementLoadingContent(element, label) {
    if (element instanceof HTMLInputElement) {
        element.value = label;
        return;
    }

    element.innerHTML = buildLoadingMarkup(label);
}

function lockElement(element, options = {}) {
    if (!isHTMLElement(element) || isElementLocked(element)) {
        return;
    }

    const state = {
        html: element instanceof HTMLInputElement ? null : element.innerHTML,
        value: element instanceof HTMLInputElement ? element.value : null,
        disabled: 'disabled' in element ? element.disabled : undefined,
        ariaDisabled: element.getAttribute('aria-disabled'),
        tabIndex: element.getAttribute('tabindex'),
        pointerEvents: element.style.pointerEvents,
    };

    elementState.set(element, state);
    trackedElements.add(element);

    element.classList.add('is-loading', 'is-double-click-locked');
    setElementLoadingContent(element, options.loadingText || 'Loading...');

    if ('disabled' in element) {
        element.disabled = true;
    }

    element.setAttribute('aria-disabled', 'true');

    if (options.disablePointerEvents) {
        element.style.pointerEvents = 'none';
    }

    if (element instanceof HTMLAnchorElement) {
        element.setAttribute('tabindex', '-1');
    }

    if (options.restoreAfter) {
        clearRestoreTimer(element);

        const timerId = window.setTimeout(() => {
            restoreElement(element);
        }, options.restoreAfter);

        elementTimers.set(element, timerId);
    }
}

function restoreElement(element) {
    const state = elementState.get(element);

    if (!state || !isHTMLElement(element)) {
        return;
    }

    clearRestoreTimer(element);

    if (element instanceof HTMLInputElement) {
        element.value = state.value ?? '';
    } else {
        element.innerHTML = state.html ?? '';
    }
    element.classList.remove('is-loading', 'is-double-click-locked');

    if ('disabled' in element && typeof state.disabled === 'boolean') {
        element.disabled = state.disabled;
    }

    if (state.ariaDisabled === null) {
        element.removeAttribute('aria-disabled');
    } else {
        element.setAttribute('aria-disabled', state.ariaDisabled);
    }

    if (state.tabIndex === null) {
        element.removeAttribute('tabindex');
    } else {
        element.setAttribute('tabindex', state.tabIndex);
    }

    element.style.pointerEvents = state.pointerEvents;

    elementState.delete(element);
    trackedElements.delete(element);
}

function lockForm(form, submitter) {
    if (!(form instanceof HTMLFormElement) || isFormLocked(form)) {
        return;
    }

    formState.set(form, { submitter: submitter || null });
    trackedForms.add(form);

    form.classList.add('is-double-click-locked');

    if (submitter && isHTMLElement(submitter)) {
        lockElement(submitter, {
            loadingText: getLoadingText(submitter, 'Processing...'),
            disablePointerEvents: true,
        });
    }
}

function restoreForm(form) {
    const state = formState.get(form);

    if (!state) {
        return;
    }

    form.classList.remove('is-double-click-locked');

    if (state.submitter) {
        restoreElement(state.submitter);
    }

    formState.delete(form);
    trackedForms.delete(form);
}

function restoreAllLocks() {
    trackedForms.forEach((form) => restoreForm(form));
    trackedElements.forEach((element) => restoreElement(element));
}

function resolveSubmitter(form, event) {
    if (event.submitter && isHTMLElement(event.submitter)) {
        return event.submitter;
    }

    if (document.activeElement instanceof HTMLElement && form.contains(document.activeElement)) {
        return document.activeElement;
    }

    return form.querySelector('button[type="submit"], input[type="submit"]');
}

function handleDocumentSubmit(event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !shouldGuardForm(form)) {
        return;
    }

    if (event.defaultPrevented) {
        return;
    }

    if (isFormLocked(form)) {
        event.preventDefault();
        return;
    }

    const submitter = resolveSubmitter(form, event);
    lockForm(form, submitter);
}

function handleGuardedLinkClick(event, link) {
    if (isElementLocked(link)) {
        event.preventDefault();
        return;
    }

    if (event.defaultPrevented && getGuardMode(link) !== 'normal') {
        return;
    }

    if (event.defaultPrevented) {
        return;
    }

    lockElement(link, {
        loadingText: getLoadingText(link, 'Loading...'),
        restoreAfter: RESTORE_TIMEOUT_MS,
        disablePointerEvents: true,
    });
}

function handleGuardedButtonClick(event, button) {
    if (isElementLocked(button)) {
        event.preventDefault();
        return;
    }

    if (event.defaultPrevented && getGuardMode(button) !== 'normal') {
        return;
    }

    if (event.defaultPrevented) {
        return;
    }

    lockElement(button, {
        loadingText: getLoadingText(button, 'Loading...'),
        restoreAfter: RESTORE_TIMEOUT_MS,
        disablePointerEvents: true,
    });
}

function handleDocumentClick(event) {
    const link = event.target instanceof Element ? event.target.closest('a[href]') : null;

    if (shouldGuardLink(link)) {
        handleGuardedLinkClick(event, link);
        return;
    }

    const button = event.target instanceof Element ? event.target.closest('button[type="button"]') : null;

    if (shouldGuardButton(button)) {
        handleGuardedButtonClick(event, button);
    }
}

function requestConfirmedSubmit(form, submitter) {
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    form.dataset.swalConfirmed = 'true';

    if (typeof form.requestSubmit === 'function') {
        if (submitter) {
            form.requestSubmit(submitter);
        } else {
            form.requestSubmit();
        }
        return;
    }

    form.submit();
}

document.addEventListener('click', handleDocumentClick, false);
document.addEventListener('submit', handleDocumentSubmit, false);
window.addEventListener('pageshow', restoreAllLocks);

window.DoubleClickGuard = {
    requestConfirmedSubmit,
    restoreElement,
    restoreForm,
};
