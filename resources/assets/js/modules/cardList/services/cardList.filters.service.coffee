'use strict'

module.exports = () ->
    class CardListFilters

        @check: (filters, authUser, card) ->
            if filters.tag isnt null and _.findIndex(card.tags, { id: filters.tag.id }) is -1
                return false

            if filters.assignedTo is 'no one' and card.users.length > 0
                return false

            if _.isObject(filters.assignedTo) and _.findIndex(card.users, { id: filters.assignedTo.id }) is -1
                return false

            if filters.quick is 'Created by me' and card.user_id isnt authUser.id
                return false

            if filters.quick is 'With subtasks' and card.subtasks and card.subtasks.length is 0
                return false

            if filters.quick is 'With impact' and !card.impact
                return false

            if filters.quick is 'With comments' and card.comments.length is 0
                return false

            if filters.quick is 'With files attached' and card.attachments and card.attachments.length is 0
                return false

            if filters.quick is 'Tasks blocked' and ! card.blocked
                return false

            if filters.quick is 'Tasks unblocked' and card.blocked
                return false

            true
