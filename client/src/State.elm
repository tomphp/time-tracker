module State exposing (init, update, subscriptions)

import Api
    exposing
        ( fetchIndex
        , fetchProjects
        , fetchProject
        , fetchProjects
        , fetchDevelopers
        )
import Material
import Material.Layout as Layout
import Types exposing (Model, Msg(..), Loadable(..))


init : ( Model, Cmd Msg )
init =
    initialModel ! [ Layout.sub0 Mdl, fetchIndex ]


initialModel : Model
initialModel =
    { apiEndpoint = "/api/v1"
    , projectsEndpoint = Nothing
    , developersEndpoint = Nothing
    , projects = Nothing
    , project = NotLoaded
    , developers = Nothing
    , mdl = Material.model
    }


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Mdl msg ->
            Material.update Mdl msg model

        Refresh ->
            ( { model | projects = Nothing, developers = Nothing }, fetchIndex )

        IndexFetched (Ok ( projects, developers )) ->
            { model
                | projectsEndpoint = Just projects
                , developersEndpoint = Just developers
            }
                ! [ fetchProjects projects
                  , fetchDevelopers developers
                  ]

        IndexFetched (Err _) ->
            ( model, Cmd.none )

        ProjectsFetched (Ok projects) ->
            ( { model | projects = Just projects }, Cmd.none )

        ProjectsFetched (Err _) ->
            ( model, Cmd.none )

        FetchProject url ->
            ( { model | project = Loading }
            , fetchProject url
            )

        ProjectFetched (Ok project) ->
            ( { model
                | project =
                    (case project of
                        Just p ->
                            Loaded p

                        Nothing ->
                            Failed
                    )
              }
            , Cmd.none
            )

        ProjectFetched (Err _) ->
            ( model, Cmd.none )

        DevelopersFetched (Ok developers) ->
            ( { model | developers = Just developers }, Cmd.none )

        DevelopersFetched (Err _) ->
            ( model, Cmd.none )


subscriptions : Model -> Sub Msg
subscriptions model =
    Layout.subs Mdl model.mdl
