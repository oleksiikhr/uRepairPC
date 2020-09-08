import server from './server';

export default (port: number, hostname: string|undefined) => server.listen(port, hostname, () => {
  console.log(`Listening on ${hostname || '*'}:${port}`);
});
