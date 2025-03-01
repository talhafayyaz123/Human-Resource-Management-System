import { Step } from "../../definition";
import { StepExtension } from "../../designer-extension";
import { createTaskStepComponentViewFactory } from "./task-step-component-view";
import { TaskStepExtensionConfiguration } from "./task-step-extension-configuration";

const defaultConfiguration: TaskStepExtensionConfiguration = {
    view: {
        paddingLeft: 50,
        paddingRight: 50,
        paddingY: 20,
        textMarginLeft: 12,
        minTextWidth: 70,
        iconSize: 22,
        radius: 5,
        inputSize: 14,
        outputSize: 10,
    },
};

export class TaskStepExtension implements StepExtension<Step> {
    public static create(
        configuration?: TaskStepExtensionConfiguration
    ): TaskStepExtension {
        return new TaskStepExtension(configuration ?? defaultConfiguration);
    }

    public readonly componentType = "task";

    private constructor(
        private readonly configuration: TaskStepExtensionConfiguration
    ) {
        this.configuration = configuration;
    }

    public readonly createComponentView = createTaskStepComponentViewFactory(
        false,
        (this.configuration ?? defaultConfiguration).view
    );
}
