import Api from "../../api/index";

class Children extends Api {
   list = () => this.rest("/children.json").then((response) => response.json());

   filter = () =>
      this.rest("/list-filtered.json", {
         method: "POST",
         "Content-Type": "application/json",
      }).then((response) => response.json());

   delete = (id) =>
      this.rest("/children/delete-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify({ id }),
      }).then(() => id);

   create = (child) =>
      this.rest("/children/add-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify(child),
      }).then(() => ({ ...child, id: new Date().getTime() }));

   update = (child) =>
      this.rest("/children/update-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify(child),
      }).then(() => child);
}

export default new Children();
