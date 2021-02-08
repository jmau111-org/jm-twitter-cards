module.exports = function (grunt) {
  "use strict";

  // Project configuration
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

    cssmin: {
      target: {
        files: [
          {
            expand: true,
            cwd: "admin/css",
            src: ["*.css", "!*.min.css"],
            dest: "admin/css",
            ext: ".min.css",
          },
        ],
      },
    },

    uglify: {
      dev: {
        files: [
          {
            expand: true,
            src: ["*.js", "!*.min.js"],
            dest: "admin/js",
            cwd: "admin/js",
            rename: function (dst, src) {
              return dst + "/" + src.replace(".js", ".min.js");
            },
          },
        ],
      },
    },
  });
  grunt.loadNpmTasks("grunt-contrib-cssmin");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.registerTask("css", ["cssmin"]);
  grunt.registerTask("js", ["uglify"]);
  grunt.registerTask("default", ["css", "js"]);
  grunt.util.linefeed = "\n";
};
