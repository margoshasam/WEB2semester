import Api from "../../api/index";

class Groups extends Api {
   list = () => this.rest("/groups.json").then((response) => response.json());

   delete = (id) =>
      this.rest("/groups/delete-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify({ id }),
      }).then(() => id);

   create = (group) =>
      this.rest("/groups/add-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify(group),
      }).then(() => ({ ...group, id: new Date().getTime() }));

   update = (group) =>
      this.rest("/groups/update-item", {
         method: "POST",
         "Content-Type": "application/json",
         body: JSON.stringify(group),
      }).then(() => group);
}

export default new Groups();
