var mysql = require("mysql2");
import config from '../services/db';

var con = mysql.createConnection({
  host: config.host,
  user: config.user,
  password: config.password,
  database: config.database
});

function getPlayer(player) {
  return new Promise(function (resolve, reject) {
    con.connect(function (err) {
      if (err) throw err;
      con.query(
      `SELECT
      users_field_data.name,
      users_field_data.login,
      profile.profile_id,
      profile__field_x.field_x_value,
      profile__field_y.field_y_value,
      profile__field_health.field_health_value

      FROM users_field_data
      LEFT JOIN profile
      ON  profile.uid                     =  users_field_data.uid
      AND type                            = 'player'
      INNER JOIN profile__field_x
      ON  profile__field_x.entity_id      =  users_field_data.uid
      INNER JOIN profile__field_y
      ON  profile__field_y.entity_id      =  users_field_data.uid
      INNER JOIN profile__field_health
      ON  profile__field_health.entity_id =  users_field_data.uid
      WHERE  users_field_data.uid         = ` + player
      ,
        function (err, result) {
          if (err) throw err;
          resolve(result);
        }
      );
    });
  });
}

function updateMap(id, map) {
  con.connect(function (err) {
    if (err) throw err;
    con.query(
      `UPDATE profile__field_map_grid SET field_map_grid_target_id = ` +
      map + ` WHERE profile__field_map_grid.entity_id = ` + id,
      function (err, result, fields) {
        if (err) throw err;
        return true;
      }
    );
  });
  return;
}

function updatePlayer(id, stat, value, replace) {
  if (replace) {
    con.connect(function (err) {
      if (err) throw err;
      con.query(
        `UPDATE profile__field_` + stat +
        ` SET field_` + stat + `_value = ` + value +
        ` WHERE profile__field_` + stat + `.entity_id = ` + id,
        function (err, result, fields) {
          if (err) throw err;
          return true;
        }
      );
    });

  } else {
    con.connect(function (err) {
      //     console.log(value);
      if (err) throw err;
      con.query(
        `UPDATE profile__field_` + stat +
        ` SET field_` + stat + `_value = field_` + stat + `_value + ` + value +
        ` WHERE profile__field_` + stat + `.entity_id = ` + id,
        function (err, result, fields) {
          if (err) throw err;
          return true;
        }
      );
    });
  }
  return;
}

module.exports = {
  getPlayer,
  updateMap,
  updatePlayer,
};