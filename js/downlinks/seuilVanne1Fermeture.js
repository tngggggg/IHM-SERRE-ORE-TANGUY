const grpc = require("@grpc/grpc-js");
const device_grpc = require("@chirpstack/chirpstack-api/api/device_grpc_pb");
const device_pb = require("@chirpstack/chirpstack-api/api/device_pb");

const server = "localhost:8080";
const devEui = "fb7ebacd80024d18";
const apiToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjaGlycHN0YWNrIiwiaXNzIjoiY2hpcnBzdGFjayIsInN1YiI6ImYwYmJjMzFhLTFjMDItNDc5NC1hZTYzLTg2MjcyZDIzNjY5NCIsInR5cCI6ImtleSJ9.01jlGWHeugvhEAcGD5zvKGRqavCao5N4k3xRrVc_aPI"; // Token ici

const seuil = parseInt(process.argv[2]);
if (isNaN(seuil)) {
  console.error("Valeur seuil non valide.");
  process.exit(1);
}

const deviceService = new device_grpc.DeviceServiceClient(
  server,
  grpc.credentials.createInsecure()
);

const metadata = new grpc.Metadata();
metadata.set("authorization", "Bearer " + apiToken);

const payload = Uint8Array.from([seuil]);

const item = new device_pb.DeviceQueueItem();
item.setDevEui(devEui);
item.setFPort(7);
item.setConfirmed(false);
item.setData(payload);

const req = new device_pb.EnqueueDeviceQueueItemRequest();
req.setQueueItem(item);

deviceService.enqueue(req, metadata, (err, resp) => {
  if (err) return console.error("Erreur gRPC :", err);
  console.log("Seuil électrovanne bac 1 (fermeture) envoyé. ID:", resp.getId());
});