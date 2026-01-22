import mongoose from "mongoose";

const MONGODB_URI = "mongodb://127.0.0.1:27017/source_table";

if (!MONGODB_URI) {
  throw new Error("Please define MongoDB URI");
}

let cached = global.mongoose;

if (!cached) {
  cached = global.mongoose = { conn: null, promise: null };
}

export async function connectDB() {
  if (cached.conn) return cached.conn;

  if (!cached.promise) {
    cached.promise = mongoose.connect(MONGODB_URI).then((mongoose) => mongoose);
  }

  cached.conn = await cached.promise;
  return cached.conn;
}
