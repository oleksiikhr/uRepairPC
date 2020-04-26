export function toNumber(value: string|undefined, defaultValue: number): number {
  if (typeof value === 'undefined') {
    return defaultValue;
  }

  return +value || defaultValue;
}

export function toBool(value: string|undefined, defaultValue = false): boolean {
  if (typeof value === 'undefined') {
    return defaultValue;
  }

  return ['true', 'yes', 'on', '1'].includes(value);
}
