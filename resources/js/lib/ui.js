export function ui(uiData, page, section, field, fallback = '') {
    const value = uiData?.[page]?.[section]?.[field];

    return value === undefined || value === null || value === '' ? fallback : value;
}
