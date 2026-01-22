import { useEffect, useState } from "react";

export default function Home() {
  const [description, setDescription] = useState("");
  const [active, setActive] = useState(true);
  const [franchiseTypes, setFranchiseTypes] = useState([]);
  const [sources, setSources] = useState([]);

  const loadSources = async () => {
    const res = await fetch("/api/sources");
    const data = await res.json();
    setSources(data);
  };

  useEffect(() => {
    loadSources();
  }, []);

  const handleSubmit = async () => {
    if (!description) return alert("Description is required");

    await fetch("/api/sources", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ description, active, franchiseTypes }),
    });

    setDescription("");
    setFranchiseTypes([]);
    setActive(true);
    loadSources();
  };

  const toggleFranchise = (value) => {
    setFranchiseTypes((prev) =>
      prev.includes(value)
        ? prev.filter((v) => v !== value)
        : [...prev, value]
    );
  };

  return (
    <>
      <div className="container">
        {/* LEFT FORM */}
        <div className="formBox">
          <h4>Source Table</h4>

          <label>ID</label>
          <input disabled />

          <label>Description *</label>
          <input
            value={description}
            onChange={(e) => setDescription(e.target.value)}
          />

          <label className="checkbox">
            <input
              type="checkbox"
              checked={active}
              onChange={() => setActive(!active)}
            />
            Active
          </label>

          <div className="franchise">
            <label>Franchise Type</label>
            <div>
              <input
                type="checkbox"
                checked={franchiseTypes.includes("ACE Rent A Car")}
                onChange={() => toggleFranchise("ACE Rent A Car")}
              />{" "}
              ACE Rent A Car
            </div>
            <div>
              <input
                type="checkbox"
                checked={franchiseTypes.includes("EuropCar")}
                onChange={() => toggleFranchise("EuropCar")}
              />{" "}
              EuropCar
            </div>
          </div>

          <div className="buttons">
            <button onClick={handleSubmit}>Save</button>
            <button
              onClick={() => {
                setDescription("");
                setFranchiseTypes([]);
                setActive(true);
              }}
            >
              New
            </button>
          </div>
        </div>

        {/* RIGHT TABLE */}
        <div className="tableBox">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Active</th>
              </tr>
            </thead>
            <tbody>
              {sources.map((s, i) => (
                <tr key={s._id}>
                  <td>{i + 1}</td>
                  <td>{s.description}</td>
                  <td>
                    <input type="checkbox" checked={s.active} readOnly />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* ✅ CSS INSIDE SAME PAGE */}
      <style jsx global>{`
        .container {
          display: flex;
          gap: 20px;
          padding: 20px;
          font-family: Arial;
        }

        .formBox {
          width: 300px;
          border: 1px solid #ccc;
          padding: 10px;
        }

        .formBox input:not([type="checkbox"]) {
          width: 100%;
          margin-bottom: 10px;
        }

        .checkbox {
          display: block;
          margin-bottom: 10px;
        }

        .franchise {
          border: 1px solid #aaa;
          padding: 5px;
          margin-top: 10px;
        }

        .buttons {
          margin-top: 10px;
        }

        .buttons button {
          margin-right: 5px;
        }

        .tableBox {
          flex: 1;
          border: 1px solid #ccc;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        th,
        td {
          border: 1px solid #aaa;
          padding: 5px;
          text-align: left;
        }

        thead {
          background: #dbe6f3;
        }
      `}</style>
    </>
  );
}
