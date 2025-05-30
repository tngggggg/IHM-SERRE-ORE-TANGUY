const grpc = require("@grpc/grpc-js");
const device_grpc = require("@chirpstack/chirpstack-api/api/device_grpc_pb");
const device_pb = require("@chirpstack/chirpstack-api/api/device_pb");

const server = "localhost:8080";  // adresse du serveur gRPC ChirpStack
const devEui = "fb7ebacd80024d18"; // DevEUI 
const apiToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjaGlycHN0YWNrIiwiaXNzIjoiY2hpcnBzdGFjayIsInN1YiI6ImYwYmJjMzFhLTFjMDItNDc5NC1hZTYzLTg2MjcyZDIzNjY5NCIsInR5cCI6ImtleSJ9.01jlGWHeugvhEAcGD5zvKGRqavCao5N4k3xRrVc_aPI"; // remplace par ton token

const deviceService = new device_grpc.DeviceServiceClient(
  server,
  grpc.credentials.createInsecure()
);

const metadata = new grpc.Metadata();
metadata.set("authorization", "Bearer " + apiToken);

const item = new device_pb.DeviceQueueItem();
item.setDevEui(devEui);
item.setFPort(4);  // port du payload
item.setConfirmed(false);
item.setData(Uint8Array.from([79])); // Envoi de la lettre 'O' (ASCII 79)

const enqueueReq = new device_pb.EnqueueDeviceQueueItemRequest();
enqueueReq.setQueueItem(item);

deviceService.enqueue(enqueueReq, metadata, (err, resp) => {
  if (err) {
    console.error("Erreur gRPC :", err);
    return;
  }
  console.log("Downlink enqueued, ID:", resp.getId());
});
