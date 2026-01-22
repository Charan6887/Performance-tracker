import mongoose from "mongoose";

const SourceSchema = new mongoose.Schema({
  description: { type: String, required: true },
  active: { type: Boolean, default: true },
  franchiseTypes: [{ type: String }],
}, { timestamps: true });

export default mongoose.models.Source ||
  mongoose.model("Source", SourceSchema);
