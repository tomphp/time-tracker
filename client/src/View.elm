module View exposing (view)

import Dict exposing (..)
import Html exposing (..)
import Material.Button as Button
import Material.List as List
import Material.Options as Options
import Material.Icon as Icon
import Material.Progress as Progress
import Material.Layout as Layout
import Material.Typography as Typography
import Material.Table as Table
import Material.Grid as Grid exposing (grid, cell, Device(..))
import Types exposing (Model, Msg(..), Project, Developer, TimeEntry)


view : Model -> Html Msg
view model =
    Layout.render
        Mdl
        model.mdl
        []
        { header =
            [ Layout.row []
                [ Options.styled span [ Typography.title ] [ text "Time Tracker" ]
                , Layout.spacer
                , Button.render Mdl
                    [ 0 ]
                    model.mdl
                    [ Button.icon
                    , Button.ripple
                    , Options.onClick Refresh
                    ]
                    [ Icon.i "refresh" ]
                ]
            ]
        , drawer = []
        , tabs = ( [], [] )
        , main = [ mainArea model ]
        }


mainArea : Model -> Html Msg
mainArea model =
    grid []
        -- [ cell [ Grid.size All 12 ]
        --     [ div [] [ text model.apiEndpoint ]
        --     , div [] [ text <| Maybe.withDefault "loading..." model.projectsEndpoint ]
        --     , div [] [ text <| Maybe.withDefault "loading..." model.developersEndpoint ]
        --     ]
        -- ] ++
        [ cell [ Grid.size All 6 ]
            [ List.ul [] (projectListHtml model) ]
        , cell [ Grid.size All 6 ]
            [ List.ul [] (developerListHtml model) ]
        , cell [ Grid.size All 12 ]
            [ projectHtml model.project ]
        ]


projectHtml : Maybe Project -> Html Msg
projectHtml project =
    case project of
        Just p ->
            div []
                [ Options.styled h2 [ Typography.display2 ] [ text p.name ]
                , timeEntryListHtml p
                ]

        Nothing ->
            text ""


projectListHtml : Model -> List (Html Msg)
projectListHtml model =
    [ Options.styled h3 [ Typography.display1 ] [ text "Projects" ] ]
        ++ (case model.projects of
                Just projects ->
                    List.map projectItemHtml <| Dict.values projects

                Nothing ->
                    [ Progress.indeterminate ]
           )


developerListHtml : Model -> List (Html Msg)
developerListHtml model =
    [ Options.styled h2 [ Typography.display1 ] [ text "Developers" ] ]
        ++ (case model.developers of
                Just developers ->
                    List.map developerItemHtml developers

                Nothing ->
                    [ Progress.indeterminate ]
           )


timeEntryListHtml : Project -> Html Msg
timeEntryListHtml project =
    Table.table []
        [ Table.thead []
            [ Table.tr []
                [ Table.th [] [ text "Date" ]
                , Table.th [] [ text "Period" ]
                , Table.th [] [ text "Description" ]
                ]
            ]
        , Table.tbody [] <| List.map timeEntryItemHtml project.entries
        ]


projectItemHtml : Project -> Html Msg
projectItemHtml project =
    List.li
        [ Options.onClick (FetchProject project.url) ]
        [ List.content []
            [ List.avatarIcon "insert chart" []
            , List.body [] [ text project.name ]
            ]
        ]


developerItemHtml : Developer -> Html Msg
developerItemHtml developer =
    List.li [ List.withSubtitle ]
        [ List.content []
            [ List.avatarIcon "person" []
            , List.body [] [ text developer.name ]
            , List.subtitle [] [ text developer.email ]
            ]
        ]


timeEntryItemHtml : TimeEntry -> Html Msg
timeEntryItemHtml entry =
    Table.tr []
        [ Table.td [] [ text entry.date ]
        , Table.td [] [ text entry.period ]
        , Table.td [] [ text entry.description ]
        ]
