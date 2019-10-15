/*
 * This class is about computer class
 */
package Electronics_equipment;

/**
 * @author Radi
 */
public class Computer extends ElectronicsEquipment{

    private String deviceName;
    private int deviceWeight;
    private int cost;
    private int powerUsage;
    
    public Computer(String menufacturerName, String deviceType, String deviceName, int deviceWeight, int cost, int powerUsage) {
        super(menufacturerName, deviceType);
        this.deviceName=deviceName;
        this.deviceWeight=deviceWeight;
        this.cost=cost;
        this.powerUsage=powerUsage;
    }
    
    @Override
    public String display(){
        String deviceDetails=("Device name : "+deviceName
                +"\nMenufacturer name : " +menufacturerName
                +"\nDevice weight :"+deviceWeight+" "+"KG"
                +"\nCost per device :$"+cost
                +"\n Power usage :"+powerUsage +" "+"wat"
                +"\n Device type: "+deviceType);
        return deviceDetails;
    }
}
