/** @see https://github.com/puppeteer/puppeteer/issues/2460#issuecomment-453864311 */
// eslint-disable-next-line
const retry: <T>(fn: () => Promise<T>, ms: number) => Promise<T> = <T>(fn: () => Promise<T>, ms: number) => new Promise<T>((resolve) => {
  fn()
    .then(resolve)
    .catch(() => {
      setTimeout(() => {
        console.log('retrying..');
        retry(fn, ms).then(resolve);
      }, ms);
    });
});

export default retry;
