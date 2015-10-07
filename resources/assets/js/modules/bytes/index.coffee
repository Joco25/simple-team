angular
    .module 'simple.team.bytes', []
    .filter 'bytes', ->
        (bytes, precision) ->
            if isNaN(parseFloat(bytes)) or !isFinite(bytes) then return '-'
            if typeof precision is 'undefined' then precision = 1

            units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB']
            number = Math.floor(Math.log(bytes) / Math.log(1024))

            (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) +  ' ' + units[number]
